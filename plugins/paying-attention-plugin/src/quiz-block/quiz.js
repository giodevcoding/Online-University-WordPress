import QuizEdit from "./quiz-edit"
import "./editor.scss"

function initializeQuizBlock() {

    createEditorLocking();

    wp.blocks.registerBlockType( "paying-attention/quiz", {
        edit: QuizEdit,
        save: (props) => {
            return null;
        }
    } );
}


function createEditorLocking() {
    let isLocked = false;
    wp.data.subscribe(() => {
        const results = wp.data.select('core/block-editor').getBlocks().filter( block => {
            return block.name == "paying-attention/quiz" && block.attributes.correctAnswer == null;
        });
        
        if (results.length > 0 && !isLocked) {
            isLocked = true;
            wp.data.dispatch("core/editor").lockPostSaving("noanswer")
        }

        if (results.length <= 0 && isLocked ) {
            isLocked = false;
            wp.data.dispatch("core/editor").unlockPostSaving("noanswer")
        }
    });
}

initializeQuizBlock();