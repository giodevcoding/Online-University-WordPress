import { InnerBlocks, useBlockProps } from "@wordpress/block-editor"

export function Banner(props) {
    const blockProps = useBlockProps.save();
    return ( 
        <div {...blockProps}>
            <InnerBlocks.Content />
        </div> 
    )
}