<?php

namespace Universum\Controller\Api;

use Universum\Model\Evento;
use Universum\Model\Inscricao;
use Universum\Service\EventoService;
use Universum\Service\InscricaoService;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since april-2023
 * @version 1.0
 */
class InscricaoController
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
            $inscricao = (new InscricaoService())->fetchById($route);

            if(!$inscricao) {
                http_response_code(400);
                exit(
                    json_encode([
                        "success" => false,
                        "message" => "Inscricao not found"
                    ])
                );
            }

            http_response_code(200);
            exit(
                json_encode($inscricao)
            );
        }

        if($route == "all") {
            $inscricoes = (new InscricaoService())->fetcAll();

            if(!$inscricoes) {
                http_response_code(400);
                exit(
                    json_encode([
                        "success" => false,
                        "message" => "no Inscricao found"
                    ])
                );
            }

            http_response_code(200);
            exit(
                json_encode($inscricoes)
            );
        }

        if($route == "evento") {
            empty($param) ? throw new InvalidArgumentException() : null;
            
            $inscricoes = (new InscricaoService())->fetchInscricaoPessoaByEvento($param);

            if(!$inscricoes) {
                http_response_code(400);
                exit(
                    json_encode([
                        "success" => false,
                        "message" => "no Inscricao found"
                    ])
                );
            }

            http_response_code(200);
            exit(
                json_encode($inscricoes)
            );
        }

        if($route == "pessoa") {
            empty($param) ? throw new InvalidArgumentException() : null;
            
            $inscricoes = (new InscricaoService())->fetchByPessoa($param);

            if(!$inscricoes) {
                http_response_code(400);
                exit(
                    json_encode([
                        "success" => false,
                        "message" => "no Inscricao found"
                    ])
                );
            }

            http_response_code(200);
            exit(
                json_encode($inscricoes)
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

        if( empty($object->fk_evento)
         || empty($object->fk_pessoa)
         || !is_bool((bool) $object->presenca)
        )
        {
        http_response_code(400);
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Required fields not provided for Inscricao"
                ])
            );
        }

        $inscricao = (new Inscricao())
            ->setEvento($object->fk_evento)
            ->setPessoa($object->fk_pessoa)
            ->setPresenca($object->presenca == 'true' ? true : false);

        if(!(new InscricaoService())->insert($inscricao))
        {
            http_response_code(400);
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Failed to insert new Inscricao"
                ])
            );
        }

        http_response_code(201);
        exit(
            json_encode([
                "success" => true,
                "message" => "Inscricao successfully created"
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

        $update = (new InscricaoService())->fetchById($object->id);
        $update->setEvento($object->fk_evento)
               ->setPessoa($object->fk_pessoa)
               ->setPresenca($object->presenca);

        if(!(new InscricaoService())->update($update))
        {
            http_response_code(400);
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Failed to update Inscricao"
                ])
            );
        }

        http_response_code(201);
        exit(
            json_encode([
                "success"  => true,
                "message"  => "Inscricao sucessfully updated"
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
            if(!(new InscricaoService())->fetchById($route))
            {
                http_response_code(400);
                exit(
                    json_encode([
                        "success" => false,
                        "message" => "Inscricao doesn't exists"
                    ])
                );
            }
            else {
                (new InscricaoService())->delete($route);

                http_response_code(201);
                exit(
                    json_encode([
                        "success" => true,
                        "message" => "Inscricao sucessfully deleted"
                    ])
                );
            }
        }
    }
}
