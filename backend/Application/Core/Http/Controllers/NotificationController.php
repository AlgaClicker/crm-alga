<?php

namespace Core\Http\Controllers;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\NotificationServiceContracts;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private AccountServiceContracts $accountService;
    private NotificationServiceContracts $notificationService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        NotificationServiceContracts $notificationService
    ){
        $this->notificationService = $notificationService;
    }

    public function actionIndex(Request $request) {
        if (gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',

            ]);
        }

        $allNotification = $this->notificationService->_getAllBy(['toAccount'=>auth()->user()],$request->get('options'));
        return  $this->sendResponse($allNotification['data'],$allNotification['options']);
    }

    public function actionNoRead(Request $request) {
        $allNotification = $this->notificationService->_getAllBy(['readed'=>'false','toAccount'=>auth()->user()],$request->get('options'));
        return  $this->sendResponse($allNotification['data'],$allNotification['options']);
    }

    public function actionRead($idNotification) {
        return  $this->sendResponse($this->notificationService->readNotification($idNotification));
    }

    public function actionSendTemplate(Request $request)
    {
        $this->validate($request, [
            'template' => 'required|in:request,notification,delivery',
            'title' => 'required|string',
            'message' => 'required|string',
            'toAccount' => 'nullable|uuid|exists:Domain\Entities\Subscriber\Account,id',
            'methods.*' => 'nullable|in:local,mail',
            'files.*' => 'nullable|exists:Domain\Entities\Services\Files,hash',
            'request.id' => 'required_if:template,request|uuid|exists:Domain\Entities\Business\Master\Requisition,id',
            'delivery.id' => 'required_if:delivery,request|uuid|exists:Domain\Entities\Business\Document\Requisition\Invoice,id',
        ]);
        return $this->notificationService->sendNotificationTemplate($request->all());
    }
    public function actionSend(Request $request) {
        $this->validate($request, [
            'title' => 'required|string',
            'message' => 'required|string',
            'toAccount' => 'nullable|uuid|exists:Domain\Entities\Subscriber\Account,id',
            'methods.*' => 'nullable|in:local,mail',
            'files.*' => 'nullable|exists:Domain\Entities\Services\Files,hash',
        ]);

        $notification = $this->notificationService->sendNotification($request->all());
        return  $this->sendResponse($notification);
    }
}
