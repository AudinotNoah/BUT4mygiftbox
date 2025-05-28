<?php

declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Exception\HttpNotFoundException;

use gift\core\application\usecases\GestionBoxServiceInterface;
use gift\core\application\usecases\GestionBoxService;

use gift\webui\providers\CsrfTokenProvider;


class GetFormBoxAction extends AbstractAction
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $newBoxData = $request->getParsedBody();
            $csrfToken = $newBoxData['csrf'];;
            try{
                (new CsrfTokenProvider())->check($csrfToken);
            }catch (\Exception $e) {
                throw new HttpNotFoundException($request, "Token CSRF invalide : " . $e->getMessage(), $e);
            }

            $userId = $request->getAttribute('user_id');

            $verifLib = filter_var($newBoxData['libelle'], FILTER_SANITIZE_SPECIAL_CHARS);
            $verifDesc = filter_var($newBoxData['description'], FILTER_SANITIZE_SPECIAL_CHARS);
            $verifMsgKdo = filter_var($newBoxData['gift_message'], FILTER_SANITIZE_SPECIAL_CHARS);

            if ($verifLib !== $newBoxData['libelle'] || 
                $verifDesc != $newBoxData['description'] || 
                $verifMsgKdo != $newBoxData['gift_message']) {
                throw new \Exception("Les données de la box contiennent des caractères non autorisés.");
            }

            $gestionBoxService = new GestionBoxService();
            $isCadeau = isset($newBoxData['is_gift']) && $newBoxData['is_gift'] == 'on' ? 1 : 0;
            $boxData = [
                'libelle' => $newBoxData['libelle'],
                'description' => $newBoxData['description'],
                'isCadeau' => $isCadeau,
                'message_kdo' => $isCadeau ? $newBoxData['gift_message'] : null,
                'csrf' => $csrfToken,
            ];
            $box = $gestionBoxService->creerBoxVide('test', $boxData);

            $view = Twig::fromRequest($request);
            return $view->render($response, 'form_nouvelle_box.twig', [
                'csrf' => $csrfToken,
            ]);
        }catch (\Exception $e) {
                throw new HttpNotFoundException($request, "Erreur lors de la création de la box : " . $e->getMessage(), $e);
            }
    }
}