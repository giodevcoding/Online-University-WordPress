import axios from "axios";

class Like {

    constructor() {

        if (document.querySelector(".like-box")) {
            axios.defaults.headers.common["X-WP-Nonce"] = universityData.nonce;
            this.likeBoxes = document.querySelectorAll(".like-box");

            this.events();
        }
        

    }

    events() {
        this.likeBoxes.forEach((box) => {
            box.addEventListener("click", e => this.clickDispatcher(e));
        });
    }

    clickDispatcher(e) {

        let likeBox = e.target.closest('.like-box');

        if (likeBox.dataset.exists == 'yes') {
            this.removeLike(likeBox);
        } else {
            this.addLike(likeBox);
        }

    }

    async addLike(likeBox) {
        let data = {
            professorID: likeBox.dataset.professorId
        };
        
        const request = await axios.post(universityData.root_url + '/wp-json/university/v1/manage-like', data)
            .then((response) => {
                likeBox.dataset.exists = "yes";

                let likeCountElement = likeBox.querySelector(".like-count");
                let likeCount = parseInt(likeCountElement.textContent, 10);

                likeCount++;
                likeCountElement.textContent = likeCount;

                likeBox.dataset.like = response.data;

                console.log(response.data);
            })
            .catch((error) => {
                console.log(error);
            });
    }

    async removeLike(likeBox) {
        let data = {
            postID: likeBox.dataset.like,
        }

        const request = await axios.delete(universityData.root_url + '/wp-json/university/v1/manage-like', {data: data})
            .then((response) => {
                likeBox.dataset.exists = "no";

                let likeCountElement = likeBox.querySelector(".like-count");
                let likeCount = parseInt(likeCountElement.textContent, 10);

                likeCount--;
                likeCountElement.textContent = likeCount;

                likeBox.dataset.like = "";

                console.log(response.data);
            })
            .catch((error) => {
                console.log(error);
            });
    }

}

export default Like;