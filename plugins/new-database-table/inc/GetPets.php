<?php

class GetPets {

    function __construct() {
        global $wpdb;

        $this->args = $this->get_args();
       
        $table_name = $wpdb->prefix . 'pets';

        $count_query = "SELECT COUNT(*) FROM $table_name ";
        $count_query .= $this->create_where_text();

        $query = "SELECT * from $table_name ";
        $query .= $this->create_where_text();
        $query .= " LIMIT 100";

        $this->count = $wpdb->get_var( $wpdb->prepare( $count_query, $this->args ) );
        $this->pets = $wpdb->get_results( $wpdb->prepare( $query, $this->args ) );
    }


    function get_args() {

        $temp = array(
            'favcolor' => sanitize_text_field( $_GET['favcolor'] ), 
            'species' => sanitize_text_field( $_GET['species'] ), 
            'minyear' => sanitize_text_field( $_GET['minyear'] ), 
            'maxyear' => sanitize_text_field( $_GET['maxyear'] ), 
            'minweight' => sanitize_text_field( $_GET['minweight'] ), 
            'maxweight' => sanitize_text_field( $_GET['maxweight'] ), 
            'favhobby' => sanitize_text_field( $_GET['favhobby'] ), 
            'favfood' => sanitize_text_field( $_GET['favfood'] ), 
        ); 

        return array_filter( $temp, function($x) {
            // If field is empty, then it considers it returning FALSE, otherwise it is returning TRUE
            return $x;
        } );
;

    }

    function create_where_text() {
        $where_query = "";

        if ( count( $this->args) ) {
            $where_query = "WHERE ";
        }

        $current_position = 0;
        foreach( $this->args as $key => $value ) {
            $where_query .= $this->specific_query( $key );
            
            if ( $current_position != count( $this->args ) - 1 ) {
                $where_query .= " AND ";
            }
            $current_position++;
        }

        return $where_query;
    }


    function specific_query( $key ) {
        switch( $key ) {
            case "minweight":
                return "petweight >= %d";
            case "maxweight":
                return "petweight <= %d";
            case "minyear":
                return "birthyear >= %d";
            case "maxyear":
                return "birthyear <= %d";
            default:
                return $key .= " = %s";
        }
    }
}


?>