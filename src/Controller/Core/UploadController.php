<?php

namespace App\Controller\Core;

use App\Controller\APIController;
use App\Entity\Core\Team\Team;
use App\Entity\LeagueOfLegends\Region\Region;
use App\Service\FileUploader;
use FOS\RestBundle\Controller\Annotations\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/upload")
 */
class UploadController extends APIController
{
    /**
     * @Post(path="/teams/{teamUuid}/logo")
     * @IsGranted("ROLE_ADMIN")
     */
    public function uploadTeamLogoAction(string $teamUuid, Request $request, FileUploader $fileUploader)
    {
        $file = $request->files->get('file');
        $team = $this->find(Team::class, $teamUuid);

        $document = $fileUploader->uploadTeamLogo($file, $team);

        return $this->serialize($document, 'get_document');
    }

    /**
     * @Post(path="/regions/{regionUuid}/logo")
     * @IsGranted("ROLE_ADMIN")
     */
    public function uploadRegionLogoAction(string $regionUuid, Request $request, FileUploader $fileUploader)
    {
        $file = $request->files->get('file');
        $region = $this->find(Region::class, $regionUuid);

        $document = $fileUploader->uploadRegionLogo($file, $region);

        return $this->serialize($document, 'get_document');
    }
}
