import ReactDOM from 'react-dom';
import FeaturedProfessor from './featured-professor-save';
import "./render.scss";

const professorComponents = document.querySelectorAll('.professor-callout');

professorComponents.forEach(div => {
    const data = JSON.parse(div.querySelector("pre").innerHTML);
    ReactDOM.render(<FeaturedProfessor {...data} />, div);
});