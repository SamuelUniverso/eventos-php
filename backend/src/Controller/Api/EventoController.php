<?php

namespace Universum\Controller\Api;

use Universum\Model\Evento;
use Universum\Service\EventoService;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since april-2023
 * @version 1.0
 */
class EventoController
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
            $evento = (new EventoService())->fetchById($route);

            if(!$evento) {
                http_response_code(400);
                exit(
                    json_encode([
                        "success" => false,
                        "message" => "Evento not found"
                    ])
                );
            }

            http_response_code(200);
            exit(
                json_encode($evento)
            );
        }

        if($route == "all") {
            $eventos = (new EventoService())->fetcAll();

            if(!$eventos) {
                http_response_code(400);
                exit(
                    json_encode([
                        "success" => false,
                        "message" => "no Evento found"
                    ])
                );
            }   

            http_response_code(200);
            exit(
                json_encode($eventos)
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
     *      "nome": "Evento",
     *      "datahora": "yyyy-mm-dd hh24:mi:ss <timestamp>
     *  }
     */
    public function post($route, $param)
    {
        $object = json_decode($param);

        if( empty($object->nome)
         || empty($object->datahora)
        )
        {
        http_response_code(400);
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Required fields not provided for Evento"
                ])
            );
        }
    
        $evento = (new Evento())
            ->setNome($object->nome)
            ->setDataHora($object->datahora);

        if(!(new EventoService())->insert($evento))
        {
            http_response_code(400);
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Failed to insert new Evento"
                ])
            );
        }

        http_response_code(201);
        exit(
            json_encode([
                "success" => false,
                "message" => "Evento successfully created"
            ])
        );
    }

    /**
     * @method put
     * 
     * @since 1.0
     *  {
     *      "id": 1,
     *      "nome: "Evento",
     *      "datahora": "yyyy-mm-dd hh24:mi:ss <timestamp>
     *  }
     */
    public function put($route, $param)
    {
        $object = json_decode($param);

        $update = (new EventoService())->fetchById($object->id);
        $update->setNome($object->nome)
               ->setDataHora($object->datahora);

        if(!(new EventoService())->update($update))
        {
            http_response_code(400);
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Failed to update Evento"
                ])
            );
        }

        http_response_code(201);
        exit(
            json_encode([
                "success"  => true,
                "message"  => "Evento sucessfully updated"
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
            if(!(new EventoService())->fetchById($route))
            {
                http_response_code(400);
                exit(
                    json_encode([
                        "success" => false,
                        "message" => "Evento doesn't exists"
                    ])
                );
            }
            else {
                (new EventoService())->delete($route);

                http_response_code(201);
                exit(
                    json_encode([
                        "success" => true,
                        "message" => "Evento sucessfully deleted"
                    ])
                );
            }
        }
    }
}
