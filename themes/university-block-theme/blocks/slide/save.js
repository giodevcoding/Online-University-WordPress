import { InnerBlocks, useBlockProps } from "@wordpress/block-editor"

export function Slide(props) {
    const blockProps = useBlockProps.save();
    return (
        <div {...blockProps}>
            <InnerBlocks.Content />
        </div>
            )
}