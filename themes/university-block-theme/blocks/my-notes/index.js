import { useBlockProps } from "@wordpress/block-editor"
import ServerSideRender from "@wordpress/server-side-render"
import metadata from "./block.json" 

import "./render.scss"

const { name } = metadata;

const settings = {
    edit: () => {
        const blockProps = useBlockProps();
        return (
            <div {...blockProps}>
                <div className="server-side-wrapper" style={{ pointerEvents: "none" }}>
                    <ServerSideRender block={name}/>
                </div>
            </div>
        ) 
    },
    save: props => null
    
}

wp.blocks.registerBlockType( { name, ...metadata }, settings);
