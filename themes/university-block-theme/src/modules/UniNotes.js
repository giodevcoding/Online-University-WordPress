import axios from "axios";

class UniNotes {

    constructor() {

        if (document.querySelector("#my-notes")) {
            axios.defaults.headers.common["X-WP-Nonce"] = universityData.nonce;
            this.myNotes = document.querySelector("#my-notes");
            this.createButton = document.querySelector(".submit-note");
            this.noteLimit = universityData.noteLimit;
    
            this.events();
        }
       
    }


    events() {

        this.myNotes.addEventListener("click", (e) => {

            if(e.target.classList.contains("delete-note") || e.target.classList.contains("fa-trash")){
                this.deleteNote(e);
            }

            if(e.target.classList.contains("edit-note") || e.target.classList.contains("fa-pencil")){
                this.editNote(e);
            }

            if(e.target.classList.contains("update-note") || e.target.classList.contains("fa-arrow-right")){
                this.saveNote(e);
            }

        });

        this.createButton.addEventListener("click", this.createNote);

    }


    async deleteNote(e) {
        const noteEl = this.findParentLI(e.target);
        const noteID = noteEl.dataset.id;

        const request = await axios.delete(universityData.root_url + '/wp-json/wp/v2/note/' + noteID)
            .then((response) => {
                //SUCCESS

                noteEl.style.height = `${noteEl.offsetHeight}px`;
                noteEl.classList.add("fade-out");

                setTimeout(() => {
                    noteEl.remove();
                }, 401);

                if(response.data.userNoteCount < this.noteLimit) {
                    document.querySelector('.note-limit-message').classList.remove('active');
                }

                console.log("Successfully deleted note" + noteID);
                console.log(response);
            })
            .catch((error) => {
                //ERROR
                console.log(error);
            });
    }


    async editNote(e) {
        const noteEl = this.findParentLI(e.target);

        if (noteEl.dataset.state == "editable") {
            this.makeNoteReadOnly(noteEl);
        } else {
            this.makeNoteEditable(noteEl);
        }
    }


    async saveNote(e) {
        const noteEl = this.findParentLI(e.target);
        const noteID = noteEl.dataset.id;

        const updatedContent = {

            'title': noteEl.querySelector('.note-title-field').value,
            'content': noteEl.querySelector('.note-body-field').value

        };

        const request = await axios.post(universityData.root_url + '/wp-json/wp/v2/note/' + noteID, updatedContent)
            .then((response) => {
                //SUCCESS

                this.makeNoteReadOnly(noteEl);

                console.log("Successfully saved note" + noteID);
                console.log(response);
            })
            .catch((error) => {
                //ERROR
                console.log(error);
            });
    }

    async createNote() {

        const titleField = document.querySelector('.new-note-title');
        const bodyField = document.querySelector('.new-note-body');

        const newNoteContent = {

            'title': titleField.value,
            'content': bodyField.value,
            'status': 'publish'

        };

        const request = await axios.post(universityData.root_url + '/wp-json/wp/v2/note/', newNoteContent)
            .then((response) => {
                
                // Check if note limit has reached
                if(response.data.hasOwnProperty('message') && response.data.message.includes("limit")) {
                    console.log("Error " + response.data.code + ": " + response.data.message);
                    document.querySelector('.note-limit-message').classList.add('active');
                    return;
                }
                
                // Create Note (since limit has not been reached)

                titleField.value = '';
                bodyField.value = '';

                const noteHTML = `
                    <li class="fade-in-calc" data-id="${response.data.id}">
                        <input class="note-title-field" value="${response.data.title.raw}" readonly>

                        <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i>Edit</span>
                        <span class="delete-note"><i class="fa fa-trash" aria-hidden="true"></i>Delete</span>

                        <textarea class="note-body-field" readonly>${response.data.content.raw}</textarea>

                        <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i>Save</span>

                    </li>`

                document.querySelector('#my-notes').insertAdjacentHTML("afterbegin", noteHTML);

                let finalHeight; 
                const newlyCreatedNote = document.querySelector("[data-id='" + response.data.id +"']");

                // give the browser 30 milliseconds to have the invisible element added to the DOM before moving on
                setTimeout(() => {  
                    finalHeight = `${newlyCreatedNote.offsetHeight}px`
                    console.log(finalHeight);
                    newlyCreatedNote.style.height = "0px"
                }, 30)

                // give the browser another 20 milliseconds to count the height of the invisible element before moving on
                setTimeout(() => {
                    newlyCreatedNote.classList.remove("fade-in-calc")
                    newlyCreatedNote.style.height = finalHeight
                }, 50)

                // wait the duration of the CSS transition before removing the hardcoded calculated height from the element so that our design is responsive once again
                setTimeout(() => {
                    newlyCreatedNote.style.removeProperty("height")
                }, 450)
               
                console.log("Successfully created note");
                console.log(response);
            })
            .catch((error) => {
                //ERROR
                console.log(error.response);
                console.log(error);
            });
    }


    makeNoteEditable(noteEl) {
        const titleField = noteEl.querySelector(".note-title-field");
        const bodyField = noteEl.querySelector(".note-body-field");
        const saveButton = noteEl.querySelector(".update-note");
        const editButton = noteEl.querySelector(".edit-note");

        titleField.removeAttribute("readonly");
        titleField.classList.add("note-active-field");

        bodyField.removeAttribute("readonly");
        bodyField.classList.add("note-active-field");

        saveButton.classList.add("update-note--visible");

        editButton.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i>Cancel';

        noteEl.dataset.state = "editable";
    }


    makeNoteReadOnly(noteEl) {
        const titleField = noteEl.querySelector(".note-title-field");
        const bodyField = noteEl.querySelector(".note-body-field");
        const saveButton = noteEl.querySelector(".update-note");
        const editButton = noteEl.querySelector(".edit-note");

        titleField.setAttribute("readonly", "");
        titleField.classList.remove("note-active-field");

        bodyField.setAttribute("readonly", "");
        bodyField.classList.remove("note-active-field");

        saveButton.classList.remove("update-note--visible");

        editButton.innerHTML = '<i class="fa fa-pencil" aria-hidden="true"></i>Edit';

        noteEl.dataset.state = "readonly";
    }


    findParentLI(el) {
        if (el.tagName != "LI") {
            return this.findParentLI(el.parentElement);
        }
        return el;
    }

}

export default UniNotes;