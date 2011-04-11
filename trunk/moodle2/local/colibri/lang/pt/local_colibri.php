<?php
/**
 * Strings for the 'local_colibri' component, portuguese
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @version	2011.0
 * @copyright 	Learning Distance Unit {@link http://ued.ipleiria.pt} - Polytechnic Institute of Leiria
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt>
 * @license	http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'EU4ALL';


//Capabilities
$string['colibri:configureplugin'] = 'Configurar as definições do módulo Colibri';


// library strings
$string['webServiceLinkFailed'] = 'Não foi possível estabelecer a ligação ao web service';
$string['unableToConnectToTheServer'] = 'Não foi possível estabelecer ligação ao servidor remoto';
$string['unableToCreateSoapClientInstance'] = 'Não foi possível criar uma instância cliente SOAP para o módulo';
$string['remoteMethodExecutionFailed'] = 'Não foi possível executar o metodo SOAP remoto';
$string['invalidMethodResponse'] = 'Resposta inválida ou inesperada';

$string['getSoapTypesFailed'] = 'Não foi possível obter a lista de tipos SOAP do servidor Colibri';
$string['getSoapFunctionsFailed'] = 'Não foi possível obter a lista de métodos SOAP do servidor Colibri';
$string['getColibriTimeFailed'] = 'Não foi possível obter a data do servidor Colibri';

$string['invalidStartDate'] = 'A data de inicio da sessão é inválida';
$string['invalidEndDate'] = 'A data de fim da sessão é inválida';
$string['invalidNumberOfParticipants'] = 'O número de participantes é inválido (a sessão deve ter participantes!)';


// settings menu
$string['colibri'] = 'Colibri';
$string['configuration'] = 'Configuração';
// configuration page
$string['colibriConfiguration'] = 'Configuração do Colibri';
$string['insuficientPermissionsToConfigureTheColibriPlugin'] = 'Não tem permissões suficientes para configurar o módulo Colibri';
$string['colibriSettingsSaved'] = 'Definições guardadas com sucesso';
$string['colibriErrorSavingSettings'] = 'Ocorreu um erro ao guardar as definições ({$a})';
// configuration form
$string['about'] = 'Sobre';
$string['usingPluginVersion'] = 'Versão do módulo local: {$a}';
$string['generalSettings'] = 'Definições Gerais';
$string['colibriWsdlUrl'] = 'Endereço do WSDL';
$string['colibriEmptyWsdlURL'] = 'O endereço para o WSDL tem de ser definido';
$string['colibriWsdlUrl_help'] = 'O endereço WSDL é a URL com o documento que descreve os serviços disponíveis no web service Colibri';
$string['instanceSettings'] = 'Definições da Instância';
$string['colibriInstallationIdentifier'] = 'Identificador da Instalação';
$string['colibriEmptyInstallationIdentifier'] = 'O identificador da instalação tem de ser definido';
$string['colibriInstallationIdentifier_help'] = 'O identificador da instalação é o idenficador único fornecido pelos serviços Colibri para utilização dos web services';
$string['colibriInstallationPassword'] = 'Chave da Instalação';
$string['colibriEmptyInstallationPassword'] = 'A chave da instalação tem de ser definida';
$string['colibriInstallationPassword_help'] = 'A chave da instalação é uma senha fornecida pelos serviços Colibri e que valida o identificador da instalação';
$string['save'] = 'Guardar';
$string['colibriInvalidWsdlUrl'] = 'Não é possível estabelecer uma ligação com o web service. Por favor, verifique a URL e tente novamente.';
