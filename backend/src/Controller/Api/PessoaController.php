<?php

namespace Universum\Controller\Api;

use Universum\Model\Pessoa;
use Universum\Service\PessoaService;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since march-2023
 * @version 1.0
 */
class PessoaController
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
                    (new PessoaService())->fetchById($route)
                )
            );
        }

        if($route == "all") {
            http_response_code(200);
            exit(
                json_encode(
                    (new PessoaService())->fetcAll()
                )
            );
        }

        if($route == "cpf") {
            empty($param) ? throw new InvalidArgumentException() : null;

            http_response_code(200);
            exit(
                json_encode(
                    (new PessoaService())->fetchByCpf($param)
                )
            );
        }

        http_response_code(401);
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
     *      "nome": "Nome",
     *      "cpf": "123456789AB
     *  }
     */
    public function post($route, $param)
    {
        $object = json_decode($param);

        if( empty($object->nome)
         || empty($object->cpf)
         )
         {
            http_response_code(400);
             exit(
                 json_encode([
                     "success" => false,
                     "message" => "Required fields not provided for Pessoa"
                 ])
             );
         }

        $pessoa = (new Pessoa())
            ->setNome($object->nome)
            ->setCpf($object->cpf);
        
        if(!(new PessoaService())->insert($pessoa))
        {
            http_response_code(400);
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Failed to insert new Pessoa"
                ])
            );
        }

        http_response_code(201);
        exit(
            json_encode([
                "success"  => true,
                "message"  => "Pessoa successfully created"
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

        $update = (new PessoaService())->fetchById($object->id);
        $update->setNome($object->nome)
               ->setCpf($object->cpf);

        if(!(new PessoaService())->update($update))
        {
            http_response_code(400);
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Failed to update Pessoa"
                ])
            );
        }

        http_response_code(201);
        exit(
            json_encode([
                "success"  => true,
                "message"  => "Pessoa sucessfully updated"
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
            if(!(new PessoaService())->fetchById($route))
            {
                http_response_code(400);
                exit(
                    json_encode([
                        "success" => false,
                        "message" => "Pessoa with id '{$route}' doesn't exists"
                    ])
                );
            }
            else {
                (new PessoaService())->delete($route);

                http_response_code(201);
                exit(
                    json_encode([
                        "success" => true,
                        "message" => "Pessoa with id '{$route}' sucessfully deleted"
                    ])
                );
            }
        }
    }
}
