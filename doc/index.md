---
layout: default
---
# submarino-sdk

SDK Não Oficial para integração a partir de aplicações PHP com as APIs da B2W Marketplace (Submarino, Shoptime, Americanas.com)

[![Build Status](https://secure.travis-ci.org/gpupo/submarino-sdk.png?branch=master)](http://travis-ci.org/gpupo/submarino-sdk)

[![Paypal Donations](https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EK6F2WRKG7GNN&item_name=submarino-sdk)

Somente a versão 4.0 ou superior é compatívivel com a API SkyHub.

## Requisitos para uso

* PHP >= *7.3*
* [curl extension](http://php.net/manual/en/intro.curl.php)
* [Composer Dependency Manager](http://getcomposer.org)

Este componente **não é uma aplicação Stand Alone** e seu objetivo é ser utilizado como biblioteca.
Sua implantação deve ser feita por desenvolvedores experientes.

**Isto não é um Plugin!**

As opções que funcionam no modo de comando apenas servem para depuração em modo de
desenvolvimento.

A documentação mais importante está nos testes unitários. Se você não consegue ler os testes unitários, eu recomendo que não utilize esta biblioteca.

## Direitos autorais e de licença

This project is licensed under the terms of the MIT license.

Este componente está sob a [licença MIT](https://github.com/gpupo/common-sdk/blob/master/LICENSE)

Para a informação dos direitos autorais e de licença você deve ler o arquivo
de [licença](https://github.com/gpupo/common-sdk/blob/master/LICENSE) que é distribuído com este código-fonte.

### Resumo da licença

Exigido:

- Aviso de licença e direitos autorais

Permitido:

- Uso comercial
- Modificação
- Distribuição
- Sublicenciamento
- Proibido

Proibido:

- Responsabilidade Assegurada

---

## Instalação

Adicione o pacote [submarino-sdk](https://packagist.org/packages/gpupo/submarino-sdk) ao seu projeto utilizando [composer](http://getcomposer.org):

    composer require gpupo/submarino-sdk:^4.0

## Links


* [Documentação oficial](https://desenvolvedores.skyhub.com.br)
* [Submarino-sdk Composer Package](https://packagist.org/packages/gpupo/submarino-sdk) no packagist.org
* [Marketplace-bundle Composer Package](https://opensource.gpupo.com/MarkethubBundle/) - Integração deste pacote com Symfony 4
