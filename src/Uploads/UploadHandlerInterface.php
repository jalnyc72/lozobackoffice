<?php

namespace Digbang\Backoffice\Uploads;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadHandlerInterface
{
    /**
     * Moves an uploaded file to the indicated path.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param $to
     *
     * @throws \Illuminate\Filesystem\FileNotFoundException if the original file is not found
     * @throws \RuntimeException                            if the upload couldn't be fulfilled
     *
     * @return void
     */
    public function save(UploadedFile $file, $to);
}
