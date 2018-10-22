<?php

namespace Translator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class LanguageController extends AbstractActionController {

    protected $vhm;
    protected $em;
    protected $ls;
    protected $ufs;
    protected $cropImageService;
    protected $imageService;
    protected $translatorService;

    public function __construct($vhm, $em, $ls, $ufs, $cropImageService, $imageService, $translatorService) {
        $this->vhm = $vhm;
        $this->em = $em;
        $this->ls = $ls;
        $this->ufs = $ufs;
        $this->cropImageService = $cropImageService;
        $this->imageService = $imageService;
        $this->translatorService = $translatorService;
    }

    public function indexAction() {
        $page = $this->params()->fromQuery('page', 1);
        $query = $this->ls->getLanguages();
        $languages = $this->ls->getLanguagesForPagination($query, $page, 10);
        if (count($languages) == 0) {
            $this->translatorService->defaultLanguages();
            $languages = $this->ls->getLanguages();
        }

        $searchString = '';
        if ($this->getRequest()->isPost()) {
            $searchString = $this->getRequest()->getPost('search');
            $query = $this->ls->searchLanguages($searchString);
            $languages = $this->ls->getLanguagesForPagination($query, $page, 10);
        }

        return new ViewModel(
                array(
            'languages' => $languages,
            'searchString' => $searchString
                )
        );
    }

    public function addAction() {
        $this->vhm->get('headScript')->appendFile('/js/upload-files.js');
        $this->vhm->get('headLink')->appendStylesheet('/css/upload-image.css');
        //Create session container for crop images
        $container = new Container('cropImages');
        $container->getManager()->getStorage()->clear('cropImages');


        $language = $this->ls->newLanguage();
        $form = $this->ls->createForm($language);

        //Create new image object and form for image
        $image = $this->imageService->createImage();
        $formImage = $this->imageService->createImageForm($image);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            $formImage->setData($this->getRequest()->getPost());
            if ($form->isValid() && $formImage->isValid()) {
                //Create image array and set it
                $imageFile = [];
                $imageFile = $this->getRequest()->getFiles('image');
                //Upload image
                if ($imageFile['error'] === 0) {
                    //Upload original file
                    $imageFiles = $this->cropImageService->uploadImage($imageFile, 'language', 'original', $image, 1);

                    if (is_array($imageFiles)) {
                        $folderOriginal = $imageFiles['imageType']->getFolder();
                        $fileName = $imageFiles['imageType']->getFileName();
                        $image = $imageFiles['image'];
                        //Upload thumb 150x100
                        $imageFiles = $this->cropImageService->resizeAndCropImage('public/' . $folderOriginal . $fileName, 'public/img/userFiles/languages/thumb/', 150, 100, '150x100', $image);
                        //Create 450x300 crop
                        $imageFiles = $this->cropImageService->createCropArray('450x300', $folderOriginal, $fileName, 'public/img/userFiles/languages/450x300/', 450, 300, $image);
                        $image = $imageFiles['image'];
                        $cropImages = $imageFiles['cropImages'];
                        //Create return URL
                        $returnURL = $this->cropImageService->createReturnURL('beheer/languages', 'index');

                        //Create session container for crop
                        $this->cropImageService->createContainerImages($cropImages, $returnURL);

                        //Save blog image
                        $this->imageService->saveImage($image);
                        //Add imgae to language
                        $language->setLanguageImage($image);
                    } else {
                        $this->flashMessenger()->addErrorMessage($imageFiles);
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('Image not uploaded');
                }
                //End upload image
                //Save Language
                $this->ls->saveLanguage($language, $this->currentUser());

                if ($imageFile['error'] === 0 && is_array($imageFiles)) {
                    return $this->redirect()->toRoute('beheer/images', array('action' => 'crop'));
                } else {
                    return $this->redirect()->toRoute('beheer/languages');
                }
            }
        }

        return new ViewModel(
                array(
            'language' => $language,
            'form' => $form,
            'formImage' => $formImage
                )
        );
    }

    public function editAction() {
        $this->vhm->get('headScript')->appendFile('/js/upload-images.js');
        $this->vhm->get('headLink')->appendStylesheet('/css/upload-image.css');

        //Create session container for crop images
        $container = new Container('cropImages');
        $container->getManager()->getStorage()->clear('cropImages');

        $id = (int) $this->params()->fromRoute('id', 0);
        if (empty($id)) {
            return $this->redirect()->toRoute('beheer/languages');
        }
        $language = $this->ls->getLanguage($id);
        if (empty($language)) {
            return $this->redirect()->toRoute('beheer/languages');
        }

        $image = $this->imageService->createImage();
        $formImage = $this->imageService->createImageForm($image);
        $form = $this->ls->createForm($language);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            $formImage->setData($this->getRequest()->getPost());
            if ($form->isValid() && $formImage->isValid()) {
                //Create image array and set it
                $imageFile = [];
                $imageFile = $this->getRequest()->getFiles('image');
                //Upload image
                if ($imageFile['error'] === 0) {
                    //Upload original file
                    $imageFiles = $this->cropImageService->uploadImage($imageFile, 'default', 'original', $image, 1);
                    if (is_array($imageFiles)) {
                        $folderOriginal = $imageFiles['imageType']->getFolder();
                        $fileName = $imageFiles['imageType']->getFileName();
                        $image = $imageFiles['image'];
                        //Upload thumb 150x100
                        $imageFiles = $this->cropImageService->resizeAndCropImage('public/' . $folderOriginal . $fileName, 'public/img/userFiles/languages/thumb/', 150, 100, '150x100', $image);
                        //Create 450x300 crop
                        $imageFiles = $this->cropImageService->createCropArray('450x300', $folderOriginal, $fileName, 'public/img/userFiles/languages/450x300/', 450, 300, $image);
                        $image = $imageFiles['image'];
                        $cropImages = $imageFiles['cropImages'];
                        //Create return URL
                        $returnURL = $this->cropImageService->createReturnURL('beheer/languages', 'index');

                        //Create session container for crop
                        $this->cropImageService->createContainerImages($cropImages, $returnURL);

                        //Save blog image
                        $this->imageService->saveImage($image);
                        //Add image to language
                        $language->setLanguageImage($image);
                    } else {
                        $this->flashMessenger()->addErrorMessage($imageFiles);
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('Image not uploaded');
                }
                //End upload image
                //Save Language
                $this->ls->updateLanguage($language, $this->currentUser());

                if ($imageFile['error'] === 0 && is_array($imageFiles)) {
                    return $this->redirect()->toRoute('beheer/images', array('action' => 'crop'));
                } else {
                    return $this->redirect()->toRoute('beheer/languages');
                }
            }
        }

        $returnURL = $this->cropImageService->createReturnURL('beheer/languages', 'edit', $id);

        return new ViewModel(
                array(
            'language' => $language,
            'form' => $form,
            'formImage' => $formImage,
            'image' => $language->getLanguageImage(),
            'returnURL' => $returnURL
                )
        );
    }

    /**
     * 
     * Action to delete the language from the database and linked images
     */
    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (empty($id)) {
            return $this->redirect()->toRoute('beheer/languages');
        }
        $language = $this->ls->getLanguage($id);
        if (empty($language)) {
            return $this->redirect()->toRoute('beheer/languages');
        }
        //Delete linked images
        $image = $language->getLanguageImage();
        if (is_object($image)) {
            $this->imageService->deleteImage($image);
        }


        $this->ls->deleteLanguage($language);
        $this->flashMessenger()->addSuccessMessage('Language removed');
        return $this->redirect()->toRoute('beheer/languages');
    }

    public function languageSelectAction() {
        if ($this->getRequest()->isPost()) {
            $id = $this->getRequest()->getPost('selectLanguage');
            $returnURL = $this->getRequest()->getPost('redirectURL');
            if(empty($returnURL)) {
                $returnURL = '/';
            }
            if (empty($id)) {
                return $this->redirect()->toUrl($returnURL);
            }
            $language = $this->ls->getLanguage($id);
            if (empty($language)) {
                return $this->redirect()->toUrl($returnURL);
            }
            $this->translatorService->setLanguageInSession($language);
            
            return $this->redirect()->toUrl($returnURL);
        }
        
    }

}
