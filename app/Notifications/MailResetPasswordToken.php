<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Helpers\MyLibrary;
use App\Helpers\Email_sender;

class MailResetPasswordToken extends Notification
{
		use Queueable;

		protected $token;

		protected $user;

		/**
		 * Create a new notification instance.
		 *
		 * @return void
		 */
		public function __construct($token,$user)
		{
				$this->token = $token;
				$this->user = $user;
				$this->user->email = MyLibrary::getDecryptedString($this->user->email); 
				$this->user->personalId = MyLibrary::getDecryptedString($this->user->personalId); 
		}

		/**
		 * Get the notification's delivery channels.
		 *
		 * @param  mixed  $notifiable
		 * @return array
		 */
		public function via($notifiable)
		{
				$data = [];
		    $data['resetToken'] = $this->token;
		    $data['user'] = $this->user;
				Email_sender::forgotPassword($data);
				return [];
		}

		/**
		 * Get the mail representation of the notification.
		 *
		 * @param  mixed  $notifiable
		 * @return \Illuminate\Notifications\Messages\MailMessage
		 */
		public function toMail($notifiable)
		{
				
				/*return (new MailMessage)->subject("Your Password Reset Link")->view(
						'auth.emails.password', ['resetToken' => $this->token,'user'=> $this->user]
				);*/

				// $data = [];
		    // $data['resetToken'] = $this->token;
		    // $data['user'] = $this->user;
		    
		    // Email_sender::forgotPassword($data);

		    $response = new MailMessage;
		    $response->view = 'auth.emails.password';
				//$response->viewData = $data;
				
				return $response;
		}

		/**
		 * Get the array representation of the notification.
		 *
		 * @param  mixed  $notifiable
		 * @return array
		 */
		public function toArray($notifiable)
		{
				return [
						//
				];
		}
}
