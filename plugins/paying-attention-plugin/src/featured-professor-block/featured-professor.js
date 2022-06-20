import FeaturedProfessorEdit from "./featured-professor-edit"
import "./editor.scss"

function initializeFeaturedProfessorBlock() {

    createEditorLocking();

    wp.blocks.registerBlockType( "paying-attention/featured-professor", {
        edit: FeaturedProfessorEdit,
        save: (props) => {
            return null;
        }
    } );
}


function createEditorLocking() {
    let isLocked = false;

    wp.data.subscribe(() => {
        const results = wp.data.select('core/block-editor').getBlocks().filter( block => {
            return block.name == "paying-attention/featured-professor" && block.attributes.profID == -1;
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

initializeFeaturedProfessorBlock();