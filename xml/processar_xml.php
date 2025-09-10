<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o arquivo foi enviado corretamente
    if (isset($_FILES['xmlFile']) && $_FILES['xmlFile']['error'] === UPLOAD_ERR_OK) {
        $xmlContent = file_get_contents($_FILES['xmlFile']['tmp_name']);

        // Faça o parsing do XML
        $xml = simplexml_load_string($xmlContent);

        // Processa o formulário de edição (se existir)
        if (isset($_POST['editCFOP'])) {
            $itemIndex = $_POST['itemIndex'];
            $newCFOP = $_POST['newCFOP'];

            // Atualiza o CFOP no XML
            $xml->NFe->infNFe->det[$itemIndex]->prod->CFOP = $newCFOP;

            // Salva as alterações de volta no arquivo
            file_put_contents($_FILES['xmlFile']['tmp_name'], $xml->asXML());
        }

        // Extraia as informações necessárias
        $numeroNF = $xml->NFe->infNFe->ide->nNF;
        $valorTotal = number_format((float)$xml->NFe->infNFe->total->ICMSTot->vNF, 2, ',', '.');
        $chaveAcesso = substr($xml->NFe->infNFe->attributes()->Id, 3); // Correção para remover a parte "NFe"
        $naturezaOperacao = $xml->NFe->infNFe->ide->natOp;


        // Informações sobre impostos
        $valorICMS = number_format((float)$xml->NFe->infNFe->total->ICMSTot->vICMS, 2, ',', '.');
        $valorIPI = number_format((float)$xml->NFe->infNFe->total->vIPI, 2, ',', '.');
        $valorPIS = number_format((float)$xml->NFe->infNFe->total->vPIS, 2, ',', '.');
        $valorCOFINS = number_format((float)$xml->NFe->infNFe->total->vCOFINS, 2, ',', '.');

        // Informações sobre o fornecedor (emitente)
        $emitente = $xml->NFe->infNFe->emit;
        $nomeEmitente = $emitente->xNome;
        $cnpjEmitente = $emitente->CNPJ;
        $dataEmissao = $xml->NFe->infNFe->ide->dhEmi; // Data e hora de emissão
        $dataSaida = $xml->NFe->infNFe->ide->dhSaiEnt; // Data e hora de saída ou entrada (NÃO ESTOU USANDO NO CODIGO)
        $enderecoEmitente = $xml->NFe->infNFe->emit->enderEmit;
        $logradouroEmitente = $enderecoEmitente->xLgr;
        $numeroEmitente = $enderecoEmitente->nro;

        // info sobre cliente
        $destinatario = $xml->NFe->infNFe->dest;
        $nomeDestinatario = $destinatario->xNome;
        $cnpjDestinatario = $destinatario->CNPJ ?: $destinatario->CPF; // CNPJ ou CPF


        $modalidadeFrete = $xml->NFe->infNFe->transp->modFrete; // 0: por conta do emitente, 1: por conta do destinatário, etc.
        $placaVeiculo = $xml->NFe->infNFe->transp->veicTransp->placa;
        $nomeTransportadora = $xml->NFe->infNFe->transp->transporta->xNome;

        $pesoBruto = $xml->NFe->infNFe->transp->vol->pesoB;
        $pesoLiquido = $xml->NFe->infNFe->transp->vol->pesoL;
        $desconto = number_format((float)$xml->NFe->infNFe->total->ICMSTot->vDesc, 2, ',', '.');
        $valorFrete = number_format((float)$xml->NFe->infNFe->total->ICMSTot->vFrete, 2, ',', '.');



        // Exiba as informações da NF
        echo "<h2>Detalhes da Nota Fiscal</h2>";
        echo "Número da NF: $numeroNF <br>";
        echo "Chave de Acesso: $chaveAcesso <br>";
        echo "Natureza da Operação: $naturezaOperacao <br>";
        echo "Valor Total: R$ $valorTotal <br>";
        echo "Data emissão: $dataEmissao <br>";

        // Exiba as informações do fornecedor
        echo "<h3>Fornecedor (Emitente)</h3>";
        echo "Nome: $nomeEmitente <br>";
        echo "CNPJ: $cnpjEmitente <br>";
        echo "Endereço: $enderecoEmitente <br>";
        echo "Logradouro: $logradouroEmitente <br>";
        echo "Numero: $numeroEmitente <br>";


        // Exiba as informações do Cliente
        echo "<h3>Cliente (Destinatario)</h3>";
        echo "Nome do Destinatário: $nomeDestinatario <br>";
        echo "Documento do Destinatário: $cnpjDestinatario <br>";
        echo "Destinatário: $destinatario <br>";

        echo "<h3>Frete:</h3>";
        echo "Modelidade frete: $modalidadeFrete <br>";
        echo "Placa do veículo: $placaVeiculo <br>";
        echo "Nome da transportadora: $nomeTransportadora <br>";

        echo "<h3> Outras informações: </h3>";
        echo "Peso bruto: $pesoBruto <br>";
        echo "Peso Liquido: $pesoLiquido <br>";
        echo "Desconto: R$ $desconto <br>";
        echo "Valor do Frete: R$ $valorFrete <br>";


        // Exiba os detalhes dos impostos
        echo "<h3>Impostos</h3>";
        echo "Valor ICMS: R$ $valorICMS <br>";
        echo "Valor IPI: R$ $valorIPI <br>";
        echo "Valor PIS: R$ $valorPIS <br>";
        echo "Valor COFINS: R$ $valorCOFINS <br><br>";

        // Exiba o formulário de edição fora do loop
        echo "<form method='post'>";
        echo "<h3>Itens da Nota Fiscal</h3>";
        echo "<ul>";
        foreach ($xml->NFe->infNFe->det as $itemIndex => $item) {
            $nomeItem = $item->prod->xProd;
            $quantidade = (float)$item->prod->qCom;
            $unidadeMedida = $item->prod->uCom;
            $aliquotaICMS = (float)$item->imposto->ICMS->ICMS00->pICMS; // ICMS modalidade 00 (Exemplo)
            $valorUnitario = number_format((float)$item->prod->vUnCom, 2, ',', '.');
            $cfop = $item->prod->CFOP;
            $codigoProduto = $item->prod->cProd;
            $ncm = $item->prod->NCM;

            echo "<br><li>Nome: $nomeItem <br> Quantidade: $quantidade <br> Medida: $unidadeMedida <br> Valor Unitário: R$ $valorUnitario <br> CFOP: $cfop <br> Cod Prod: $codigoProduto <br> NCM: $ncm <br> %ICMS: $aliquotaICMS";
            echo "</li>";
        }
        echo "</ul>";
        echo "</form>";
    } else {
        echo "Erro no upload do arquivo.";
    }
}

// ... (o código existente para processar o XML permanece igual)

include_once '../conexao.php';
// Verificar a conexão
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Salvar o arquivo XML na pasta "xml"
$nomeArquivo = "xml/nf_" . $numeroNF . ".xml";
file_put_contents($nomeArquivo, $xml->asXML());


// Inserir dados na tabela
$sql = "INSERT INTO importaxml (
    numeroNF, 
    chaveAcesso, 
    valorTotal, 
    valorICMS, 
    valorIPI, 
    valorPIS, 
    valorCOFINS, 
    cfop, 
    unidadeMedida, 
    xmlFilePath, 
    nomeFornecedor, 
    cnpjFornecedor, 
    nomeDestinatario, 
    cnpjDestinatario, 
    modalidadeFrete, 
    placaVeiculo, 
    nomeTransportadora, 
    pesoBruto, 
    pesoLiquido, 
    desconto, 
    valorFrete, 
    naturezaOperacao
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);
$stmt->bind_param(
    'isssssssssssssssssssss',
    $numeroNF,
    $chaveAcesso,
    $valorTotal,
    $valorICMS,
    $valorIPI,
    $valorPIS,
    $valorCOFINS,
    $cfop,
    $unidadeMedida,
    $nomeArquivo,
    $nomeFornecedor,
    $cnpjFornecedor,
    $nomeDestinatario,
    $cnpjDestinatario,
    $modalidadeFrete,
    $placaVeiculo,
    $nomeTransportadora,
    $pesoBruto,
    $pesoLiquido,
    $desconto,
    $valorFrete,
    $naturezaOperacao
);

$numeroNF = (int)$xml->NFe->infNFe->ide->nNF;
$valorTotal = (float)$xml->NFe->infNFe->total->ICMSTot->vNF;
$valorICMS = (float)$xml->NFe->infNFe->total->ICMSTot->vICMS;
$valorIPI = (float)$xml->NFe->infNFe->total->vIPI;
$valorPIS = (float)$xml->NFe->infNFe->total->vPIS;
$valorCOFINS = (float)$xml->NFe->infNFe->total->vCOFINS;
$cfop = '';  // Defina um valor padrão ou ajuste conforme necessário
$unidadeMedida = $item->prod->uCom;
$nomeFornecedor = $emitente->xNome;  // Substitua pelo nome real do fornecedor
$cnpjFornecedor = $emitente->CNPJ;  // Substitua pelo CNPJ real do fornecedor

$stmt->execute();

$stmt->close();
$conexao->close();

?>

<!-- Adicione um botão/link para a página editar_xml.php -->
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>XML</title>
    <style>
        body {
            background: linear-gradient(to bottom, #99d6ff, #e6f2ff);
            /* Gradiente mais suave e atraente */
            color: #333333;
            /* Cor de texto mais suave */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Fonte mais moderna */
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            /* Centraliza o conteúdo horizontalmente */
            font-weight: 500;
        }

        h2,
        h3 {
            color: #005580;
            /* Destaque para os títulos com um tom azul escuro */
            text-align: center;
            margin-bottom: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        ul li {
            background-color: rgb(2, 2, 2);
            /* Fundo mais suave para os itens */
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            /* Cantos arredondados */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            /* Sombra suave para dar profundidade */
        }

        a {
            font-weight: bold;
            color: #0066cc;
            /* Azul moderno para links */
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: color 0.3s, border-bottom 0.3s;
            /* Transições suaves para hover */
        }

        a:hover {
            color: #003366;
            /* Mudança de cor no hover */
            border-bottom: 2px solid #003366;
            /* Sutileza no hover */
        }

        body {
            background: linear-gradient(to bottom, #2a9d8f, #264653);
            color: #f4f4f9;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        h2 {
            font-size: 2.5rem;
            color: #e9c46a;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        form {
            background: #1d3557;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        form:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }

        form input[type="file"] {
            margin-top: 10px;
            margin-bottom: 20px;
            width: 100%;
            padding: 10px;
            border: 1px solid #e9c46a;
            border-radius: 8px;
            background-color: #f4f4f9;
            color: #264653;
            transition: border 0.3s;
        }

        form input[type="file"]:focus {
            border: 1px solid #e76f51;
            outline: none;
        }

        form input[type="submit"] {
            background-color: #e76f51;
            color: #f4f4f9;
            font-size: 1.1rem;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #e63946;
            transform: translateY(-3px);
        }

        a button {
            background-color: #2a9d8f;
            color: #f4f4f9;
            font-size: 1.1rem;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        a button:hover {
            background-color: #21867a;
            transform: translateY(-3px);
        }

        p {
            margin-top: 30px;
            background: #1d3557;
            padding: 20px;
            border-radius: 10px;
            color: #f4f4f9;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            line-height: 1.8;
            text-align: justify;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            form {
                padding: 20px;
            }

            p {
                padding: 15px;
            }

            h2 {
                font-size: 2rem;
            }
        }

        button {
            background-color: #008CBA;
            /* Cor do botão azul atraente */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .back-btn {
            display: inline-block;
            background-color: #007BFF;
            /* Azul elegante */
            color: #fff;
            /* Texto branco */
            font-size: 16px;
            /* Tamanho da fonte */
            font-weight: bold;
            /* Negrito */
            padding: 10px 20px;
            /* Espaçamento interno */
            border-radius: 5px;
            /* Bordas arredondadas */
            text-decoration: none;
            /* Remove o sublinhado */
            transition: all 0.3s ease;
            /* Animação suave */
        }

        .back-btn:hover {
            background-color: #0056b3;
            /* Azul mais escuro ao passar o mouse */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Sombra elegante */
            transform: translateY(-2px);
            /* Efeito de elevação */
        }

        .back-btn:active {
            background-color: #004085;
            /* Azul ainda mais escuro ao clicar */
            transform: translateY(0);
            /* Reseta o efeito de elevação */
        }

        button:hover {
            background-color: #005f73;
            /* Cor do hover para o botão */
        }

        /* Pequenos ajustes para dispositivos móveis */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            ul li {
                font-size: 14px;
                /* Texto menor para mobile */
            }
        }
    </style>
</head>
<link rel="shortcut icon" href="monitor.png" type="image/x-icon">

<body>
    <a href="editar_xml.php" class="back-btn">Listagem de importações</a>
    <br>
    <a href="index.php" class="back-btn">Início</a>
</body>

</html>