<?php
?>

<html>
<head>
<title>Establish Wager</title>
</head>
<body>
<h2>Establish Wager</h2>
<table>
<tr>
<th>Wager Name</th>
<th><input type="text" name="wagername"></input></th>
</tr>
<tr>
<th>Name of Left Side</th>
<th><input type="text" name="leftbetname"></input></th>
</tr>
<tr>
<th>Name of Right Side</th>
<th><input type="text" name="rightbetname"></input></th>
</tr>
<tr>
<th>Active Time (in seconds)</th>
<th><input type="number" name="activetime"></input></th>
</tr>
<tr>
<th>Minimum Bet</th>
<th><input type="number" name="minbet"></input></th>
</tr>
<tr>
<th>House Cut (%)</th>
<th><input type="number" name="housecut"></input></th>
</tr>
<tr>
<th>Token Address</th>
<th><input type="text" name="tokenaddress"></input></th>
</tr>
</table>
<p id="metamask"></p>
<p id="contractlink"></p>
</body>
</html>

<script type='text/javascript' src='SimpleWagerERC20.js'></script>
<script type='text/javascript' src='TokenERC20.js'></script>
<script type='text/javascript'>

// designate network
// "1" for main net
// "3" for Ropsten test net
var supportedNetworkType = "3";
var host='<?php echo $SERVER['HTTP_REFERER']; ?>';

function checkNetwork(callback) {
  var supportedNetworkName;
  switch (supportedNetworkType) {
    case "1":
      supportedNetworkName = "Main Net";
      break;

    case "3":
      supportedNetworkName = "Ropsten";
      break;

    case "4":
      supportedNetworkName = "Rinkeby";
      break;

    case "42":
      supportedNetworkName = "Kovan";
      break;

    default:
      supportedNetworkName = supportedNetworkType;
      break;
  }

  web3js.version.getNetwork(function (error, netId) {
    if (!error) {
      if (netId == supportedNetworkType) {
        callback();
      }
      else {
        alert("Please switch network to " + supportedNetworkName + ".");
      }
    }
    else {
      console.log(error);
    }
  });
}

function deployWager() {
    checkNetwork(function() {
        var wagerName = document.getElementsByName('wagername')[0].value;
        var leftBetName = document.getElementsByName('leftbetname')[0].value;
        var rightBetName = document.getElementsByName('rightbetname')[0].value;
        var activeTime = document.getElementsByName('activetime')[0].value;
        var minBet = document.getElementsByName('minbet')[0].value;
        var houseCut = document.getElementsByName('housecut')[0].value;
        var tokenAddress = document.getElementsByName('tokenaddress')[0].value;

        if (wagerName == '' || typeof wagerName === 'undefined') {
            alert("Please enter a wager name.");
            return;
        }

        if (leftBetName == '' || typeof leftBetName === 'undefined') {
            alert("Please enter a name for left side.");
            return;
        }

        if (rightBetName == '' || typeof rightBetName === 'undefined') {
            alert("Please enter a name for right side.");
            return;
        }

        if (leftBetName == rightBetName) {
            alert("Name of left side must be different from right side.");
            return;
        }

        if (activeTime <= 0 || typeof activeTime === 'undefined') {
            alert("Please enter a valid active time.");
            return;
        }

        if (minBet < 0 || typeof wagerName === 'undefined') {
            alert("Please enter a valid minimum bet.");
            return;
        }

        houseCut = Math.floor(houseCut);
        if (houseCut < 0 || houseCut >= 100 || typeof wagerName === 'undefined') {
            alert("Please enter a valid house cut.");
            return;
        }

        if (tokenAddress == '' || typeof tokenAddress === 'undefined') {
            alert("Please enter a token address.");
            return;
        }

        var tokenerc20 = web3js.eth.contract(tokenerc20ContractABI).at(tokenAddress);
        tokenerc20.name(function(error, tokenName) {
            tokenerc20.symbol(function(error, tokenSymbol) {
                var confirmTokenStr = "Are you sure you want to use this token for your wager?\n" + 
                    "Token name: " + tokenName + "\n" +
                    "Token symbol: " + tokenSymbol + "\n" + 
                    "Token address: " + tokenAddress;
                if (confirm(confirmTokenStr)) {
                    var confirmStr = "Deply a wager with:\n" +
                        "\tWager name: " + wagerName + "\n" +
                        "\tLeft Side: " + leftBetName + "\n" +
                        "\tRight Side: " + rightBetName + "\n" +
                        "\tActive Time: " + activeTime + " seconds\n" +
                        "\tMinimum Bet: " + minBet + " " + tokenSymbol + "\n" + 
                        "\tHouse Cut: " + houseCut + "%\n\n" + 
                        "It takes some time to deploy wager. Please wait patiently for the wager link to appear.";
                    if (confirm(confirmStr)) {
                        document.getElementById('metamask').innerHTML = '';
                        document.getElementById('contractlink').innerHTML = 'Your wager link will appear here when ready.';
                        var simplewagerContract = web3js.eth.contract(contractABI);
                        var minBetWei = web3js.toWei(minBet, 'ether');
                        var simplewager = simplewagerContract.new(wagerName, leftBetName, rightBetName, activeTime, minBetWei, houseCut, tokenAddress, {
                            from: web3js.eth.defaultAccount,
                            data: contractCode,
                            gas: '4700000'
                        }, function(error, contract) {
                            if (!error) {
                                if (typeof contract.address !== 'undefined') {
                                    document.getElementById('contractlink').innerHTML = 'Your wager is ready at <a href="' + host + 'betERC20.php?addr=' + contract.address + '" >Wager Page</a>';
                                    console.log("Contract address: " + contract.address);
                                    runApp();
                                }
                            }
                            else {
                                document.getElementById('contractlink').innerHTML = '';
                                alert("Something is wrong, please try again later.");
                                console.log(error);
                            }
                        });
                    }
                }
            });
        });
    });
}

function runApp() {
    checkNetwork(function() {
        document.getElementById('metamask').innerHTML = '<button type="button" onclick="deployWager()">Deploy</button>';
    });
}

function startApp() {
    console.log("StartApp");
    runApp();
}

window.addEventListener('load', function() {
  // Checking if Web3 has been injected by the browser (Mist/MetaMask)
  if (typeof web3 !== 'undefined') {
    // Use Mist/MetaMask's provider
    web3js = new Web3(web3.currentProvider);
    startApp();
  } else {
    console.log('No web3? You should consider trying MetaMask!')
    // fallback - use your fallback strategy (local node / hosted node + in-dapp id mgmt / fail)
    document.getElementById('metamask').innerHTML = '<a href="https://metamask.io"><img src="download-metamask.png"></a>';
  }
});

</script>
