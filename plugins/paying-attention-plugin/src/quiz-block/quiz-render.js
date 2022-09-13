import ReactDOM from 'react-dom';
import Quiz from './quiz-save';
import "./render.scss";

//window.addEventListener('load', (e) => {

    const quizComponents = document.querySelectorAll('.paying-attention-quiz');

    console.log(quizComponents);

    quizComponents.forEach(function(div) {
        const data = JSON.parse(div.querySelector("pre").innerHTML);
        ReactDOM.render(<Quiz {...data} />, div);
    });
    
//});
