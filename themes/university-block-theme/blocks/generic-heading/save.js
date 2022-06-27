import { RichText, useBlockProps } from "@wordpress/block-editor"
import { create } from "@wordpress/icons/build-types";

export function GenericHeading(props) {

    function createTagName() {
        switch( props.attributes.size ){
            case "large":
                return "h1";
            case "medium":
                return "h2";
            case "small":
                return "h3";
        }
    }

    const blockProps = useBlockProps.save({
        tagName: createTagName(),
        className: `headline headline--${props.attributes.size}`,
        value: props.attributes.text
    });
    return (
            <RichText.Content {...blockProps} />
    )
}