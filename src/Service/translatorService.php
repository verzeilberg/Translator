<?php

namespace Agenda\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;

/*
 * Entities
 */
use Agenda\Entity\Agenda;

class agendaService implements agendaServiceInterface {

    /**
     * Constructor.
     */
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * Get array of agendas
     *
     * @return      array
     *
     */
    public function getAgendas() {

        $agendas = $this->entityManager->getRepository(Agenda::class)
                ->findBy([], ['dateCreated' => 'DESC']);
        
        return $agendas;
    }
    
        /**
     *
     * Get blog object based on id
     *
     * @param       id  $id The id to fetch the blog from the database
     * @return      object
     *
     */
    public function getAgendaFormById($id) {
        $agendaForm = $this->entityManager->getRepository(Agenda::class)
                ->findOneBy(['id' => $id], []);

        return $agendaForm;
    }
    
        /**
     *
     * Delete a Blog object from database
     * @param       blog $blog object
     * @return      void
     *
     */
    public function deleteAgendaForm($agendaForm) {
        $this->entityManager->remove($agendaForm);
        $this->entityManager->flush();
    }

    /**
     *
     * Send mail
     * 
     * @param       agenda $agenda object
     * @return      void
     *
     */
    public function sendMail($agenda) {

        $agenda_to_adress = $agenda->getEmail();
        $agenda_to_name = $agenda->getName();
        $subject = $agenda->getSubject();
        $message = $agenda->getMessage();
        $salutation = $agenda->getSalutation();

        //$baseurl = $this->getBaseUrl();
        //$config = $this->getConfig();
        $agenda_template = 'module/Agenda/templates/send_agenda_agenda.phtml';
        //Sender information
        $mail_sender_agenda = 'noreply@verzeilberg.nl';
        $mail_sender_name = 'Verzeilberg';
        //Reply infomrtaion
        $mail_reply_agenda = 'noreply@verzeilberg.nl';
        $mail_reply_name = 'Verzeilberg';
        //Mail subject
        $mail_subject = 'Agenda met Verzeilberg: ' . $subject;

        ob_start();
        require_once($agenda_template);
        $agenda_body = ob_get_clean();

        $mail = new Mail\Message();
        $mail->setEncoding("UTF-8");

        $html = new MimePart($agenda_body);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($html));

        $mail->setBody($body);
        $mail->setFrom($mail_sender_agenda, $mail_sender_name);
        $mail->addReplyTo($mail_reply_agenda, $mail_reply_name);
        $mail->addTo($agenda_to_adress, $agenda_to_name);
        $mail->setSubject($mail_subject);


        $transport = new Mail\Transport\Sendmail();

        try {
            $transport->send($mail);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    /**
     *
     * Get base url
     * 
     * @return      string
     *
     */
    public function getBaseUrl() {
        $helper = $this->getServerUrl();
        return $helper->__invoke($this->request->getBasePath());
    }

}
