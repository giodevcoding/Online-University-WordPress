import ReactDOM from 'react-dom';
import Quiz from './quiz-save';
import "./render.scss";

const quizComponents = document.querySelectorAll('.paying-attention-quiz');

quizComponents.forEach(div => {
    const data = JSON.parse(div.querySelector("pre").innerHTML);
    ReactDOM.render(<Quiz {...data} />, div);
});