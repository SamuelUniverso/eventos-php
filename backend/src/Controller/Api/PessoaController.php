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
            exit(
                json_encode(
                    (new PessoaService())->fetchById($route)
                )
            );
        }

        if($route == "all") {
            exit(
                json_encode(
                    (new PessoaService())->fetcAll()
                )
            );
        }

        if($route == "cpf") {
            empty($param) ? throw new InvalidArgumentException() : null;
            
            exit(
                json_encode(
                    (new PessoaService())->fetchByCpf($param)
                )
            );
        }
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
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Failed to insert new Pessoa"
                ])
            );
        }

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
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Failed to update Pessoa"
                ])
            );
        }

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
                exit(
                    json_encode([
                        "success" => false,
                        "message" => "Pessoa with id '{$route}' doesn't exists"
                    ])
                );
            }
            else {
                (new PessoaService())->delete($route);

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
