<?php

namespace Core\Exceptions;

use Core\Events\NotificationEvent;
use Domain\Contracts\Services\NotificationServiceContracts;
use Domain\Entities\Services\Notification;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Core\Http\Response\JsonResponseDefault;
use Illuminate\Http\Response;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Domain\Contracts\Services\AccountServiceContracts;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];
    private AccountServiceContracts $accountService;
    private NotificationServiceContracts $notificationService;


    public function __construct(AccountServiceContracts $accountService,NotificationServiceContracts $notificationService)
    {
        $this->accountService = $accountService;
        $this->notificationService = $notificationService;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        $json = help()->json->entityToJson('Handler report(Throwable $exception)');

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (!$exception->getCode()) {
            $status = Response::HTTP_BAD_REQUEST;
        } else {
            $status = $exception->getCode();
        }


        if (Auth::user()) {
            $accountOption = $this->accountService->optionsAccount();
            if ((array_key_exists("debug_json",$accountOption) && $accountOption['debug_json']=="true") || env('APP_ENV') == "prod") {
                $messages = $exception->getMessage();
                if (property_exists($exception,'validator')) {
                    $messages = [];
                    $status = Response::HTTP_BAD_REQUEST;
                    foreach ($exception->validator->errors()->getMessages() as $message) {

                        $messages[] = $message[0];
                    }
                    //$messages = $exception->validator->errors()->getMessages();
                }
                $notification = new Notification();
                $notification->setToAccount(auth()->user());
                $notification->setFromAccount(auth()->user());
                $notification->setMessage($exception->getMessage());
                $notification->setTitle("Ошибка сервера $status ".$exception->getMessage());
                Log::info($exception);
                event(new NotificationEvent($notification));
                //event()
                //$this->notificationService->sendNotificationSystemAccount(Auth::user(),"Ошибка сервера $status",$exception->getMessage());
                return response()->json([
                    'success' => false,
                    'error' => true,
                    'code' => $status,
                    'message' => $messages
                ], $status);
            }

            Log::info(Auth::user()->getAuthIdentifier());
            Log::info($exception->getTraceAsString());
            //$this->notificationService->sendNotificationSystemAccount(Auth::user(),"Ошибка сервера $status",$exception->getMessage());


        }

        if (property_exists($exception,'validator')) {
            return response()->json([
                'success' => false,
                'error' => true,
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $exception->getMessage()
            ], 400);

        }

        if (env('APP_DEBUG') == true && Auth::user()) {
            $accountOption = $this->accountService->optionsAccount();
            if (array_key_exists("debug_json",$accountOption) && $accountOption['debug_json'] === false) {

                return parent::render($request, $exception);
            }
        }

        if (env('APP_DEBUG') == true && !Auth::user()) {
                return parent::render($request, $exception);
        }

        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'error' => true,
                'code' => $status,
                'message' => $exception->getMessage()
            ], $status);
        }


        return response()->json([
            'success' => false,
            'error' => true,
            'code' => $status,
            'message' => $exception->getMessage()
          ], $status);
    }
}
