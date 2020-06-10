<?php


namespace App;


class ObjectHelper
{
    public static function hydrate($object, array $data, array $fields): void
    {
        foreach ($fields as $field) {
            //jeya min class Form
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
            $object->$method($data[$field]);

            /* $post
                 ->setName($_POST['name'])
                 ->setContent($_POST['content'])
                 ->setSlug($_POST['slug'])
                 ->setCreatedAt($_POST['created_at']);
            */
        }
    }
}