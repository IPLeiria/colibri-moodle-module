<?php
/**
 * Strings for the 'local_colibri' component, portuguese
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 * SVN:
 * $Author$
 * $Date$
 * $Rev$
 */

$string['pluginname'] = 'Colibri';
$string['local_colibri'] = $string['pluginname'];


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

$string['checkAccessFailed'] = 'Não foi possível validar as credenciais no servidor Colibri';

$string['invalidName'] = 'O nome da sessão é inválido';
$string['invalidStartDate'] = 'A data de inicio da sessão é inválida';
$string['invalidEndDate'] = 'A data de fim da sessão é inválida';
$string['invalidNumberOfParticipants'] = 'O número de participantes é inválido (a sessão deve ter participantes!)';
$string['createSessionFailed'] = 'Não foi possível criar a sessão no servidor Colibri';
$string['modifySessionFailed'] = 'Não foi possível modificar a sessão {$a} no servidor Colibri';
$string['removeSessionFailed'] = 'Não foi possível remover a sessão {$a} do servidor Colibri';
$string['getSessionInfoFailed'] = 'Não foi possível obter informações sobre a sessão no servidor Colibri';
$string['getSessionsInfoFailed'] = 'Não foi possível obter informações sobre as sessões no servidor Colibri';

$string['errorReturned'] = 'Ocorreu um erro ({$a})';
$string['GENERIC_ERROR'] = 'Erro: {$a}';
$string['SOAP_INSTANCE_ERROR'] = 'Erro na instância do cliente SOAP';
$string['SOAP_FAULT'] = 'Erro de SOAP';
$string['EXCEPTION'] = 'Ocorreu uma excepção';
$string['INVALID_START_DATE'] = 'Data de inicio inválida';
$string['INVALID_END_DATE'] = 'Data de fim inválida';
$string['INVALID_NUMBER_OF_PARTICIPANTS'] = 'Número de participantes inválido';
$string['ERROR_ON_METHOD_RESPONSE'] = 'Erro na resposta do método remoto';
$string['INVALID_ACCESS_CREDENTIALS'] = 'As credenciais são inválidas';
$string['MISSING_ACCESS_CREDENTIALS'] = 'As credenciais não foram enviadas no pedido';
$string['UNKOWN_ERROR'] = 'Erro desconhecido: {$a}';
$string['COULD_NOT_CREATE_SESSION'] = 'Não foi possível criar a sessão';
$string['MISSING_SESSION_NAME'] = 'O nome da sessão não se encontra definido';
$string['SESSION_START_TIME_GREATER_THAN_ENDTIME'] = 'A data de inicio é superior ou igual à data de fim';
$string['COULD_NOT_GET_SESSION_INFO'] = 'Não foi possivel obter informações sobre a sessão';
$string['INSUFFICIENT_PERMISSIONS'] = 'Permissões insuficientes';
$string['COULD_NOT_MODIFY_SESSION'] = 'Não foi possível modificar a sessão';
$string['COULD_NOT_REMOVE_SESSION'] = 'Não foi possível remover a sessão';
$string['INVALID_NAME'] = 'Nome de sessão inválido';

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
$string['colibriDirectAccessUrl'] = 'URL de acesso direto';
$string['emptyColibriDirectAccessUrl'] = 'A URL the acesso direto não pode ser nula';
$string['colibriDirectAccessUrl_help'] = 'A URL base que permite o acesso direto a uma sessão';
$string['instanceSettings'] = 'Definições da Instância';
$string['colibriInstallationIdentifier'] = 'Identificador da Instalação';
$string['colibriEmptyInstallationIdentifier'] = 'O identificador da instalação tem de ser definido';
$string['colibriInstallationIdentifier_help'] = 'O identificador da instalação é o idenficador único fornecido pelos serviços Colibri para utilização dos web services';
$string['colibriInstallationPassword'] = 'Chave da Instalação';
$string['colibriEmptyInstallationPassword'] = 'A chave da instalação tem de ser definida';
$string['colibriInstallationPassword_help'] = 'A chave da instalação é uma senha fornecida pelos serviços Colibri e que valida o identificador da instalação';
$string['sessioninformationupdatemethod'] = 'Informação sobre as Sessões';
$string['sessioninformationupdatemethod_help'] = 'Método que deve ser utilizado para obter informação sobre cada uma das sessões. As opções são \'online\', para obter as informações diretamente a partir do serviço Colibri, \'cron\' para ober as informações a partir da base de dados local e sincronizar a informação entre serviços utilizando o cron, ou \'local\' para utilizar apenas as informações que constam na base de dados local.';
$string['live'] = 'online';
$string['cron'] = 'cron';
$string['local'] = 'local';
$string['save'] = 'Guardar';
$string['colibriInvalidWsdlUrl'] = 'Não é possível estabelecer uma ligação com o web service. Por favor, verifique a URL e tente novamente.';
