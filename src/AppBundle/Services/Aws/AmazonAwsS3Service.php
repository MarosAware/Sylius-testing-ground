<?php

namespace AppBundle\Services\Aws;

use Aws\S3\S3Client;
use Gaufrette\Adapter\AwsS3;
use Gaufrette\Filesystem;
use Knp\Bundle\GaufretteBundle\DependencyInjection\Factory\AwsS3AdapterFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class AmazonS3Service
 *
 * @package Acme\DemoBundle\Service
 */
class AmazonAwsS3Service
{
    private static $allowedMimeTypes = array(
        'image/jpeg',
        'image/png',
        'image/gif',
    );

    /**
     * @var S3Client
     */
    private $client;
    /**
     * @var string
     */
    private $bucket;
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param string $bucket
     * @param array  $s3arguments
     */
    public function __construct($bucket, array $s3arguments)
    {
        $this->setBucket($bucket);
        $this->setClient(new S3Client($s3arguments));
    }

//    public function upload(UploadedFile $file)
//    {
//        // Check if the file's mime type is in the list of allowed mime types.
//        if (!in_array($file->getClientMimeType(), self::$allowedMimeTypes)) {
//            throw new \InvalidArgumentException(sprintf('Files of type %s are not allowed.', $file->getClientMimeType()));
//        }
//
//        // Generate a unique filename based on the date and add file extension of the uploaded file
//        $filename = sprintf('%s/%s/%s/%s.%s', date('Y'), date('m'), date('d'), uniqid(), $file->getClientOriginalExtension());
//
//        /** @var AwsS3 $adapter */
//        $adapter = $this->filesystem->getAdapter();
//        if ($adapter instanceof AwsS3) {
//            $adapter->setMetadata($filename, array('contentType' => $file->getClientMimeType()));
//        }
//
//        $adapter->write($filename, file_get_contents($file->getPathname()));
//
//        return $filename;
//    }



    /**
     * @param string $fileName
     * @param string $content
     * @param array  $meta
     * @param string $privacy
     * @return string file url
     */
    public function upload($fileName, $content, array $meta = [], $privacy = 'public-read')
    {
        return $this->getClient()->upload($this->getBucket(), $fileName, $content, $privacy, [
            'Metadata' => $meta
        ])->toArray()['ObjectURL'];
    }
    /**
     * @param string       $fileName
     * @param string|null  $newFilename
     * @param array        $meta
     * @param string       $privacy
     * @return string file url
     */
    public function uploadFile($fileName, $newFilename = null, array $meta = [], $privacy = 'public-read') {
        if(!$newFilename) {
            $newFilename = basename($fileName);
        }
        if(!isset($meta['contentType'])) {
            // Detect Mime Type
            $mimeTypeHandler = finfo_open(FILEINFO_MIME_TYPE);
            $meta['contentType'] = finfo_file($mimeTypeHandler, $fileName);
            finfo_close($mimeTypeHandler);

            // Check if the file's mime type is in the list of allowed mime types.
            if (!in_array($meta['contentType'], self::$allowedMimeTypes)) {
                throw new \InvalidArgumentException(sprintf('Files of type %s are not allowed.', $meta['contentType']));
            }


//            var_dump(basename($fileName));
//            var_dump($fileName);
//            var_dump($mimeTypeHandler);
//            var_dump($meta['contentType']);
        }
        return $this->upload($newFilename, file_get_contents($fileName), $meta, $privacy);
    }
    /**
     * Getter of client
     *
     * @return S3Client
     */
    protected function getClient()
    {
        return $this->client;
    }
    /**
     * Setter of client
     *
     * @param S3Client $client
     *
     * @return $this
     */
    private function setClient(S3Client $client)
    {
        $this->client = $client;
        return $this;
    }
    /**
     * Getter of bucket
     *
     * @return string
     */
    protected function getBucket()
    {
        return $this->bucket;
    }
    /**
     * Setter of bucket
     *
     * @param string $bucket
     *
     * @return $this
     */
    private function setBucket($bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }
}
