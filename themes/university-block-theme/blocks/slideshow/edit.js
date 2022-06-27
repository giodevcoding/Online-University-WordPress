import { InnerBlocks, useBlockProps } from "@wordpress/block-editor"
import { useEffect } from "@wordpress/element"

export function SlideshowEdit(props) {
    const ALLOWED_BLOCKS = ['online-university/slide']

    const blockProps = useBlockProps({
        style: {
            backgroundColor: "#333",
            padding: "35px"
        }
    })
    return (
        <div {...blockProps}>
            <p style={{ textAlign: "center", fontSize: "20px", color: "#FFF" }}>Slideshow</p>
            <InnerBlocks allowedBlocks={ALLOWED_BLOCKS}/>
            <div style={{ backgroundColor: "#FFFFFFAA", marginTop: "15px" }}>
                <InnerBlocks.ButtonBlockAppender />
            </div>
        </div>
    );
}