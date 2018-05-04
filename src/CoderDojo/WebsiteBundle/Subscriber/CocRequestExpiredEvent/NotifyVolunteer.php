<?php

namespace CoderDojo\WebsiteBundle\Subscriber\CocRequestExpiredEvent;

use CoderDojo\WebsiteBundle\Entity\CocRequest;
use CoderDojo\WebsiteBundle\Event\CocRequestExpiredEvent;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Swift_Mailer;
use Symfony\Bundle\TwigBundle\TwigEngine;

class NotifyVolunteer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var TwigEngine
     */
    private $templating;

    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * NotifySlack constructor.
     * @param Swift_Mailer $mailer
     * @param TwigEngine $templating
     * @param Registry $doctrine
     */
    public function __construct(
        Swift_Mailer $mailer,
        TwigEngine $templating,
        Registry $doctrine
    ) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->doctrine = $doctrine;
    }

    public function notify(CocRequestExpiredEvent $event)
    {
        /** @var CocRequest $coc */
        $coc = $this->doctrine->getRepository(CocRequest::class)->find($event->getId());

        $message = \Swift_Message::newInstance()
            ->setSubject(sprintf('🚫 VOG Aanvraag Verlopen'))
            ->setFrom('contact@coderdojo.nl', 'CoderDojo Nederland')
            ->setReplyTo('contact@coderdojo.nl')
            ->setTo($coc->getEmail(), $coc->getLetters().' '.$coc->getName())
            ->setBcc('chris+vogrequest@coderdojo.nl')
            ->setContentType('text/html')
            ->setBody(
                $this->templating->render(
                    ':Dashboard/Email/Coc:expired_volunteer.html.twig',
                    array(
                        'coc' => $coc
                    )
                )
            );

        $this->mailer->send($message);
    }
}
