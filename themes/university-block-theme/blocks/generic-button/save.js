import { InnerBlocks, useBlockProps } from "@wordpress/block-editor"

export function GenericButton(props) {
    const blockProps = useBlockProps.save({
        href: "#",
        className: `btn btn--${props.attributes.size} btn--${props.attributes.colorName}`
    });
    return <a {...blockProps}>{props.attributes.text}</a>
}