import { button as icon } from "@wordpress/icons"
import { GenericButtonEdit as edit } from "./edit";
import { GenericButton as save } from "./save";
import metadata from './block.json'

import "./editor.scss"
import "./render.scss"

const { name } = metadata;

const settings = {
    apiVersion: 2,
    icon,
    //example: {}
    edit,
    save
}

wp.blocks.registerBlockType( { name, ...metadata }, settings);
