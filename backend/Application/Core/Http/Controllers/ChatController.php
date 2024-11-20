<?php

namespace Core\Http\Controllers;
use Domain\Contracts\Services\AccountServiceContracts;
use Illuminate\Http\Request;
use Domain\Contracts\Services\ChatServiceContracts;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid as UuidValidator;

class ChatController extends Controller
{
    private ChatServiceContracts $chatService;
    private AccountServiceContracts $accountService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ChatServiceContracts $chatService,AccountServiceContracts $accountService)
    {
        $this->chatService = $chatService;
        $this->accountService = $accountService;
    }

    public function actionGetListMessages(Request $request)
    {
        if ($request->get('options')) {
            $options = $request->get('options');
        } else {
            $options['pagginate']['limit'] = 10;
        }

        $options['orderBy'] = ['createdAt'=>"DESC"];

        $news = $this->chatService->_getAllBy(['accountTo'=>auth()->user()],$options);

        return  $this->sendResponse($news['data'],$news['options']);
    }

    public function actionSendMessages(Request $request) {
        $this->validate($request, [
            'message' => 'required|string|min:1',
            'account_to' => 'required|uuid|exists:Domain\Entities\Subscriber\Account,id',
            'files.*' => 'nullable|string|exists:Domain\Entities\Services\Files,hash',
        ]);
        $newMessage = $this->chatService->sendMessage($request->get("message"),$request->get("account_to"),$request->get('files'));
        return  $this->sendResponse($newMessage);
    }

    public function actionPrivateChat($uuidAccount,Request $request) {

        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }


        if (UuidValidator::isValid($uuidAccount) && $this->accountService->getAccountMyCompnay($uuidAccount)) {
            if ($request->get('options')) {
                $options = $request->get('options');
            } else {
                $options['pagginate']['limit'] = 10;
            }

            $options['orderBy'] = ['createdAt'=>"DESC"];
            $messages = $this->chatService->getMessagesAccount($uuidAccount,$options);
            return  $this->sendResponse($messages['data'],$messages['options']);

        } else {
            return  $this->sendResponse("не корректно указан аккаунт",'','400');
        }
    }

    public function actionReadedChat($uuidChat) {
        if (!UuidValidator::isValid($uuidChat)) {
            return  $this->sendResponse("Ошибка UUid");
        }
        $message = $this->chatService->readedMessage($uuidChat);
        return  $this->sendResponse($message);
    }

    public function actionListAccountChat(Request $request) {
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }
        return  $this->sendResponse($this->chatService->readedListChats($request->get('options')));
    }

    public function actionDeleteMessage(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Services\Chat,id',
        ]);
        return  $this->sendResponse($this->chatService->deleteMessage($request->get('id')));
    }
}
