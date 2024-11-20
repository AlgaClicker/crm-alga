<?php

namespace Domain\Services;


use Auth;
use Core\Events\ChatEvent;
use Core\Events\NotificationEvent;
use Core\Exceptions\ApplicationException;
use Domain\Contracts\Repository\Services\ChatRepositoryContracts;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\ChatServiceContracts;
use Domain\Contracts\Services\FileServiceContracts;
use Domain\Contracts\Services\NotificationServiceContracts;
use Domain\Entities\Business\Master\Requisition;
use Domain\Entities\Services\Chat;
use Domain\Entities\Services\Notification;

class ChatService extends AbstractService implements ChatServiceContracts
{
    private ChatRepositoryContracts $chatRepository;
    protected AccountServiceContracts $accountService;
    protected FileServiceContracts $fileService;
    private NotificationServiceContracts $notificationService;

    public function __construct(
        ChatRepositoryContracts $chatRepository,
        AccountServiceContracts $accountService,
        FileServiceContracts $fileService,
        NotificationServiceContracts $notificationService
    ){
        $this->chatRepository = $chatRepository;
        $this->accountService = $accountService;
        $this->fileService = $fileService;
        $this->notificationService = $notificationService;
        parent::__construct($chatRepository);
    }

    public function sendMessage($message,$message_to=null, $files=[]) {
        $body['message'] = $message;
        $body['room'] = $this->accountService->getMyCompany()->getId()->toString();

        if ($files) {
            foreach ($files as $file) {
                if ($this->fileService->getHashFile($file)) {
                    $body['files'][] = $this->fileService->getHashFile($file);
                }
            }
        }


        if ($this->accountService->getAccountMyCompnay($message_to)) {
            $body['accountTo'] = $this->accountService->getOne($message_to)->getId()->toString();
            $message = $this->chatRepository->create($body);
            event(new ChatEvent($message));
            $this->notificationService->sendNotificationSystemAccount($body['accountTo'],'Чат','Новое сообщение от пользователя:'.$this->accountService->getOne($message_to)->getUsername());
        } else {
            throw new ApplicationException('Аккаунт с ID ['.$message_to.'] не найден',404);
        }




        return $this->_getById($message->getId());
    }

    public function listMessage($room,array $options = null) {
        $optionsDef = [
            "orderBy" => [
                "created_at" => "DESC"
            ]
        ];

        if ($options) {
            $options = array_merge($options,$optionsDef);
        }
        return $this->_getAllBy(['room'=>$room,'accountTo'=>null,'requisition'=>null],$options);
    }

    public function sendPrivateMessage($message,$account) {

    }

    public function readedMessage($chatId) {
        $chatMessage = $this->_getById($chatId);


        if ($chatMessage) {
            if ($chatMessage->getAccountTo()==auth()->user()) {
                $chatMessage->setReadedAt(new \DateTimeImmutable('now'));
                $this->chatRepository->save($chatMessage);
                return $chatMessage;
            }
        }
        throw new ApplicationException("Сообщение с ID: [$chatId] не найдено",404);
    }

    public function newChatRequisition(Requisition $requisition,$message,$title='') {
        $chat = new Chat();

        $body['message'] = $message;
        if ($requisition->getManager()) {
            if ($requisition->getManager() === auth()->user()) {
                $body['accountTo'] = $requisition->getAutor();
            } else {
                $body['accountTo'] = $requisition->getManager();
            }

        } else {
            throw new ApplicationException("Назначьте менеджера!",400);
        }

        $body['room'] = auth()->user()->getCompany()->getId()->toString();
        $body['requisition'] = $requisition;

        $body['autor'] = auth()->user();
        $newMessage = $this->chatRepository->new($body);
        $notification = new Notification();
        $notification->setCreatedAt(new \DateTimeImmutable('now'));
        $notification->setFromAccount(auth()->user());
        $notification->setToAccount($body['accountTo']);
        $title = "Новое сообщение в заявке №".$requisition->getNumber();
        $notification->setTitle($title);
        $notification->setMessage($body['message']);
        event(new NotificationEvent($notification));
        event(new ChatEvent($newMessage));
        return $newMessage;
    }

    public function getListChatRequisition(Requisition $requisition,$options=[]) {
            return $this->chatRepository->getMessagesRequisition($requisition,$options);
    }

    public function sendMessageChatRequisition(Requisition $requisition, $message,$files=[]) {
        $chat = new Chat();

        $body['message'] = $message;
        if ($requisition->getManager()) {
            if ($requisition->getManager() === auth()->user()) {
                $body['accountTo'] = $requisition->getAutor();
            } else {
                $body['accountTo'] = $requisition->getManager();
            }

        } else {
            throw new ApplicationException("Назначьте менеджера!",400);
        }
        foreach ($files as $file) {
            //$chat->addFile($file);
            $body['files'][] = $file;
        }

        $body['room'] = auth()->user()->getCompany()->getId()->toString();
        $body['requisition'] = $requisition;

        $body['autor'] = auth()->user();
        $newMessage = $this->chatRepository->new($body);
        $notification = new Notification();
        $notification->setCreatedAt(new \DateTimeImmutable('now'));
        $notification->setFromAccount(auth()->user());
        $notification->setToAccount($body['accountTo']);
        $title = "Новое сообщение в заявке №".$requisition->getNumber();
        $notification->setTitle($title);
        $notification->setMessage($body['message']);
        event(new NotificationEvent($notification));
        event(new ChatEvent($newMessage));
        return $this->chatRepository->findOne($newMessage);
    }

    public function readedListChats($options=[]) {

        $account = $this->accountService->getAccountMyCompnay(auth()->user());
        return $this->chatRepository->getListChatAccount($account,$options);
    }

    public function getMessagesAccount($uuidAccount,array $options=[]) {
        return $this->chatRepository->getMessagesAccount($uuidAccount,$options);
    }

    public function deleteMessage($idMessage) {
        return $this->chatRepository->deleteMessage($idMessage);
    }

    public function toArray() {
        return get_object_vars($this);
    }
}
