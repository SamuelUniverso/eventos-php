<?php

namespace Universum\Controller\Api;

use Universum\Model\Usuario;
use Universum\Service\UsuarioService;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since april-2023
 * @version 1.0
 */
class UsuarioController
{
    /**
     * @method get
     * @param array
     * 
     * @since 1.0
     */
    public function get($route, $param)
    {
        if(is_numeric($route)) {
            http_response_code(200);
            exit(
                json_encode(
                    (new UsuarioService())->fetchById($route)
                )
            );
        }

        if($route == "all") {
            http_response_code(200);
            exit(
                json_encode(
                    (new UsuarioService())->fetcAll()
                )
            );
        }

        http_response_code(400);
        exit(
            json_encode([
                "success" => false,
                "message" => "Bad request"
            ])
        );
    }

    /**
     * @method post
     * 
     * @since 1.0
     *  {
     *      "usuario": "admin",
     *      "senha": "<sha256-hash>
     *  }
     */
    public function post($route, $param)
    {
        $object = json_decode($param);

        if( empty($object->usuario)
         || empty($object->senha)
         )
         {
            http_response_code(400);
             exit(
                 json_encode([
                     "success" => false,
                     "message" => "Required fields not provided for Usuario"
                 ])
             );
         }

        $usuario = (new Usuario())
            ->setUsuario($object->usuario)
            ->setSenha($object->senha);
        
        if(!(new UsuarioService())->insert($usuario))
        {
            http_response_code(400);
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Failed to insert new Usuario"
                ])
            );
        }

        http_response_code(201);
        exit(
            json_encode([
                "success"  => true,
                "message"  => "Usuario successfully created"
            ])
        );
    }

    /**
     * @method put
     * 
     * @since 1.0
     *  {
     *      "id": 1,
     *      "nome: "Nome",
     *      "cpf": "123456789AB
     *  }
     */
    public function put($route, $param)
    {
        $object = json_decode($param);

        $update = (new UsuarioService())->fetchById($object->id);
        $update->setUsuario($object->usuario)
               ->setSenha($object->senha);

        if(!(new UsuarioService())->updaste($update))
        {
            http_response_code(400);
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Failed to update Usuario"
                ])
            );
        }

        http_response_code(201);
        exit(
            json_encode([
                "success"  => true,
                "message"  => "Usuario sucessfully updated"
            ])
        );
    }

    /**
     * @method delete
     * 
     * @since 1.0
     */
    public function delete($route, $param)
    {
        if(is_numeric($route)) {
            if(!(new UsuarioService())->fetchById($route))
            {
                http_response_code(400);
                exit(
                    json_encode([
                        "success" => false,
                        "message" => "Usuario with id '{$route}' doesn't exists"
                    ])
                );
            }
            else {
                (new UsuarioService())->delete($route);

                http_response_code(201);
                exit(
                    json_encode([
                        "success" => true,
                        "message" => "Usuario with id '{$route}' sucessfully deleted"
                    ])
                );
            }
        }
    }
}
