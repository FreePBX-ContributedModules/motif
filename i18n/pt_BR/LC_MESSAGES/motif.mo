��             +         �     �     �     �     �  �   
     
       1   5  1   g  )   �     �  '   �  	   �     �               /     E     _     {  #   �  �   �     _  B  f  "   �  <   �  >   	  ]   H     �  %   �     �  �  �     �
     �
     �
     �
  3       B     ]  :   y  9   �  3   �     "  3   )  	   ]     g     x     �     �  #   �  "   �      �  8     �   Q     !  O  (  #   x  M   �  A   �  k   ,     �  4   �     �                                                    
                                                                     	                        Inbound Routes  Outbound Routes  Trunk Add Google Voice Account Add a stealth greeting so Google Voice is forced to pass the call when you want unanswered calls to go to GoogleVoice Voicemail (above). WARNING: The PBX will always answer, however if the PBX goes offline then GoogleVoice Voicemail will pick the call up. Advanced Settings Always Answer (IVR Mode) Automatically Add Inbound Routes for this Account Automatically Add Outbound Route for this Account Automatically Add this Account as a Trunk Buddies Can not create Google Voice/Motif table Connected Detailed Status Disconnected Google Voice (Motif) Google Voice Password Google Voice Phone Number Google Voice Status Message Google Voice Username Send Unanswered to Google Voicemail Send unanswered calls to Google voicemail after 25 seconds<br />Note: You MUST route this to a device that can answer. Example: IVRs and Phone directories can NOT answer Status This is the priority of where google will route an inbound call. A higher number has a higher priority. We believe that:<ul><li>Windows Gtalk client is 20</li><li>GMail is 24</li><li>Pidgin would be 0 or 1</li></ul>The range of acceptable values is -128 to +127. Anything else will be reset to the highest or lowest value. This is your Google Voice Password This is your Google Voice Phone Number <br />10 Digit Format This is your Google Voice Status Message that buddies will see This is your google voice login.<br />If don't you supply '@domain' we will append @gmail.com Typical Settings Unknown/Not Supported database type:  XMPP Priority Project-Id-Version: PACKAGE VERSION
Report-Msgid-Bugs-To: 
POT-Creation-Date: 2017-04-06 13:29-0400
PO-Revision-Date: 2016-12-13 02:13+0200
Last-Translator: Alexander <alexander.schley@paranagua.pr.gov.br>
Language-Team: Portuguese (Brazil) <http://weblate.freepbx.org/projects/freepbx/motif/pt_BR/>
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Language: pt_BR
Plural-Forms: nplurals=2; plural=n != 1;
X-Generator: Weblate 2.4
  Rotas de Entrada  Rotas de Saída  Tronco Adicionar conta do Google Talk Adicione uma saudação para que o Google Voice seja forçado a passar a chamada quando quiser que as chamadas não atendidas sejam acessadas no correio de voz do Google Voice (acima). AVISO: O PBX sempre responderá, no entanto, se o PBX ficar offline, o correio de voz do Google Voice receberá a chamada. Configurações Avançadas Sempre Responder (Modo URA) Adicionar Rotas de Entrada Automaticamente para esta Conta Adicionar Rotas de Saída Automaticamente para esta Conta Adicionar esta Conta Automaticamente como um Tronco Amigos Não é possível criar a tabela Google Voice/Motif Conectado Status Detalhado Desconectado Google Voice (Motif) Senha do Google Voice Número do Telefone do Google Voice Mensagem de Status do Google Voice Nome de Usuário do Google Voice Enviar não respondidas para Correio de Voz Google Voice Enviar chamadas não atendidas para o correio de voz do Google após 25 segundos <br/> Nota: Você DEVE encaminhar isso para um dispositivo que possa responder. Exemplo: URAs e telefones não podem responder Status Esta é a prioridade de onde o Google roteará uma chamada de entrada. Um número maior tem uma prioridade mais alta. Acreditamos que: <ul><li>O cliente Gtalk do Windows é 20</li><li>O Gmail é 24</li><li>Pidgin seria 0 ou 1</li></ul> Valores de -128 a +127. Qualquer outra coisa será redefinida para o valor mais alto ou mais baixo. Esta é a sua senha do Google Voice Este é o seu número de telefone do Google Voice <br/>Formato de 10 dígitos Esta é a mensagem de status do Google Voice que os amigos verão Este é o seu login do google voice. <br/>Se você não fornecer um '@domínio', acrescentaremos @gmail.com Configurações Típicas Tipo de banco de dados desconhecido/não suportado:  Prioridade XMPP 