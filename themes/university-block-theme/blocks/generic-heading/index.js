import { GenericHeadingEdit as edit } from "./edit";
import { GenericHeading as save } from "./save";
import metadata from './block.json'

import "./editor.scss"
import "./render.scss"

const { name } = metadata;

const settings = {
    apiVersion: 2,
    icon: "heading",
    //example: {}
    edit,
    save
}

wp.blocks.registerBlockType( { name, ...metadata }, settings);
