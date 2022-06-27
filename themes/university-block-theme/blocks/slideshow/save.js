import { InnerBlocks, useBlockProps } from "@wordpress/block-editor"

export function Slideshow(props) {
    const blockProps = useBlockProps.save();
    return <InnerBlocks.Content {...blockProps} />
}