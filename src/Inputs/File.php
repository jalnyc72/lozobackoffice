<?php

namespace Digbang\Backoffice\Inputs;

use Digbang\Backoffice\Controls\ControlInterface;
use Digbang\Backoffice\Uploads\UploadHandlerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class File.
 */
class File extends Input implements InputInterface
{
    protected $uploadHandler;

    public function __construct(UploadHandlerInterface $uploadHandler, ControlInterface $control, $name, $value = null)
    {
        parent::__construct($control, $name, $value);

        $this->uploadHandler = $uploadHandler;
    }

    public function save($to)
    {
        $file = $this->value();
        if (!$file instanceof UploadedFile) {
            throw new \UnexpectedValueException('Cannot move a file without upload');
        }

        if (!$file->isValid()) {
            throw new \InvalidArgumentException('File uploaded is invalid');
        }

        $this->uploadHandler->save($file, $to);
    }

    public function changeName($name)
    {
        return new self($this->uploadHandler, $this->control, $name, $this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function isFile(): bool
    {
        return true;
    }
}
