# Payment Checkout PagSeguro for LifterLMS

O [Payment Checkout PagSeguro for LifterLMS](https://www.linknacional.com/wordpress/plugins/) é um plugin extensão para o LifterLMS, que ativa o pagamento por checkout PagSeguro.

## Dependencias

O plugin Payment Checkout PagSeguro for LifterLMS é dependente da ativação do plugin LifterLMS, por favor, tenha certeza de que ele esteja instalado e apropriadamente configurado antes de iniciar a instalação do Payment Checkout PagSeguro for LifterLMS.

## Instalação

1) Na sidebar do Wordpress, procure pela opção "Plugins" e selecione-a;

<p align="start"><img src="https://github.com/LinkNacional/payment-checkout-pagseguro-for-lifterlms/assets/127407085/aa97a4f3-59e5-48f5-b085-39820ac0775f" height="250" width="auto" alt="Inst1"></p>

2) Aperte o botão "Adicionar novo" ao lado do título "Plugins" no topo da página;

<p align="start"><img src="https://github.com/LinkNacional/payment-checkout-pagseguro-for-lifterlms/assets/127407085/abacc8cd-d534-4733-a816-ff40425c82b3" height="250" width="auto" alt="Inst2"></p>

3) Aperte o botão "Enviar Plugin" ao lado do título "Instalar Plugins" no topo da página. Irá aparecer novas opções no centro da tela, selecione "Procurar...", busque pelo arquivo do plugin (payment-checkout-pagseguro-for-lifterlms.zip) e o envie;

4) Aperte o botão "Instalar agora", em seguida ative o plugin instalado;

<p align="start"><img src="https://github.com/LinkNacional/payment-checkout-pagseguro-for-lifterlms/assets/127407085/9587b933-993a-4423-8a74-3ff33d48eb74" height="250" width="auto" alt="Inst3"></p>

Ao terminar esses passos, o Payment Checkout PagSeguro for LifterLMS estára ativado e pronto para ser configurado.

## Instruções de uso

1) Agora, na sidebar do Wordpress, procure pela opção "Plugins" e selecione-a;

<p align="start"><img src="https://github.com/LinkNacional/payment-checkout-pagseguro-for-lifterlms/assets/127407085/f0750751-2909-4199-9070-6c4db9541231" height="250" width="auto" alt="Inst4"></p>

2) Selecione a opção "Configurações" abaixo do nome do plugin "LifterLMS PagSeguro";

<p align="start"><img src="https://github.com/LinkNacional/payment-checkout-pagseguro-for-lifterlms/assets/127407085/27804538-4b35-4f94-b1fc-8ca5cd7ba611" height="250" width="auto" alt="Inst5"></p>

3) Procure a opção "Ativar / Desativar" e clique nela, com isso o método de pagamento será ativado;

<p align="start"><img src="https://github.com/LinkNacional/payment-checkout-pagseguro-for-lifterlms/assets/127407085/3be70f58-fa36-4f07-b26f-7799adeceb13" height="250" width="auto" alt="Inst5"></p>

4) Procure o campo "E-mail do PagSeguro" e preencha de acordo com o informado na legenda do campo;

5) Procure o campo "Token PagSeguro" e preencha de acordo com o informado na legenda do campo;

6) Procure a opção "Tipo de ambiente" e selecione de acordo com a sua preferência;

<p align="start"><img src="https://github.com/LinkNacional/payment-checkout-pagseguro-for-lifterlms/assets/127407085/f1fb6cc8-362d-4757-95b1-2c51c1ed771b" height="250" width="auto" alt="Inst6"></p>

7) Configure o restante do método de pagamento de acordo com suas necessidades;

8) Então, clique no botão "Salvar as Alterações" no canto superior direito da página;

Agora o Payment Checkout PagSeguro for LifterLMS estará ativo e funcionando.

## Notas de desenvolvimento

### Documentações para o desenvolvimento

- Wordpress Plugin Development: <https://developer.wordpress.org/plugins/>
- LifterLMS: <https://github.com/gocodebox/lifterlms>

### Integração com PagSeguro API v1

- Referência API: <https://dev.pagbank.uol.com.br/v1/reference/checkout-pagseguro>
- Código de Checkout: <https://dev.pagbank.uol.com.br/v1/reference/checkout>
- Consulta de transação: <https://dev.pagbank.uol.com.br/v1/reference/consulta>

### Estrutura de pastas

- `/admin/`: contém o arquivo onde é executado as funções para o lado administrativo do plugin, que consiste na definição das configurações do gateway.
- `/includes/`: contém diversos arquivos responsáveis pelo funcionamento do plugin.
- `/public/`: contém o arquivo onde é executado as funções para o lado público do plugin, como estilização, entre outras. E também o arquivo responsável pelas funcionamento do gateway, como apresentação da área de pagamento, mudança de forma de pagamento, criação e processamento de pedido, atualização do status do pedido, entre outras.
