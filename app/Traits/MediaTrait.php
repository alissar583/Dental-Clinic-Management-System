<?php

namespace App\Traits;

trait MediaTrait
{
    public function mediaUrl($collection)
    {
        if ($collection)
            $medias = $this->getMedia($collection);
        // else
        //     $medias = $this->getMedia();

        $mediasResult = [];
        foreach ($medias as $mediaUUID) {
            $mediasResult[] = $mediaUUID->getUrl();
        }
        return $mediasResult;
    }
}
