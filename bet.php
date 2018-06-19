<?php
if (isset($_GET["addr"])) {
  $contract = $_GET["addr"];
}
else {
  $str = 'No addr parameter. Use a link generated from <a href="' . $SERVER['HTTP_REFERER'] . 'house.php" >house.php</a>.';
  exit($str);
}
?>

<html>
<head>
<title>Bet</title>
</head>
<body>
<p id="bet">
<h2 id="wagername"></h2>
<table>
<tr>
<th>Bet</th>
<th>Amount</th>
<th></th>
</tr>
<tr>
<th id="leftbet"></th>
<th id="leftamount"></th>
<th id="leftwon"></th>
<th id="leftplacebet"></th>
<th id="leftwinning"></th>
</tr>
<tr>
<th id="rightbet"></th>
<th id="rightamount"></th>
<th id="rightwon"></th>
<th id="rightplacebet"></th>
<th id="rightwinning"></th>
</tr>
</table>
<p id="endtime"></p>
<p id="housecut"></p>
<p id="claimhousecut"></p>
<p id="housecutamount"></p>
</p>
<p id="metamask">
</p>
</body>
</html>

<script type='text/javascript' src='SimpleWager.js'></script>
<script type='text/javascript'>

// designate network
// "1" for main net
// "3" for Ropsten test net
var supportedNetworkType = "3";
var simplewager;

function claimWinning(address) {
  simplewager.winningAlreadyClaimed(address, function(error, claimed) {
    if(!error) {
      if (claimed) {
        alert("You already claimed your winning!");
      }
      else {
        simplewager.claim(function(error, result) {
          if (!error) {
            if (result) {
              alert("Your winning is claimed. Please wait a moment for the winngins to be transfered to your account.");
              refreshApp(result);
            }
            else {
              alert("Something went wrong. Please try again later.");
            }
          }
          else {
            console.log(error);
          }
        });
      }
    }
    else {
      console.log(error);
    }
  });
}

function claim(left) {
  checkNetwork(function() {
    var address = web3js.eth.defaultAccount;
    if (left) {
      simplewager.leftBettingAmount(address, function(error, result) {
        if (!error) {
          if (result.isZero()) {
            alert("You have no winning to claim!");
          }
          else {
            if (!confirm("Do you want to claim your winning?\nNOTE: Fees may apply when claiming your winning. Do not repeat or you may lose fees.")) {
              return;
            }

            claimWinning(address);
          }
        }
        else {
          console.log(error);
        }
      });
    }
    else {
      simplewager.rightBettingAmount(address, function(error, result) {
        if (!error) {
          if (result.isZero()) {
            alert("You have no winning to claim!");
          }
          else {
            claimWinning(address);
          }
        }
        else {
          console.log(error);
        }
      });
    }
  });
}

function doClaimHouseCut() {
  simplewager.houseCutAlreadyClaimed(function(error, result) {
    if (!error) {
      if (result) {
        alert("House cut was already claimed.");
      }
      else {
        simplewager.claimHouseCut(function(error, result) {
          if (!error) {
            alert("House cut is successfully claimed.");
            refreshApp(result);
          }
          else {
            alert("Something is wrong, please try again later.");
            console.log(error);
          }
        });
      }
    }
    else {
      console.log(error);
    }
  });
}

function claimHouseCut() {
  checkNetwork(function() {
    simplewager.house(function(error, house) {
      if(!error) {
        if (house == web3js.eth.defaultAccount) {
          if (!confirm("Do you want to claim house cut?\nNOTE: Fees may apply when claiming house cut. Do not repeat or you may lose fees.")) {
            return;
          }

          doClaimHouseCut();
        }
        else {
          alert("Only house can claim house cut.");
        }
      }
      else {
        console.log(error);
      }
    });
  });
}

function declareWinnerWithName(left, name) {
  if (confirm("Do you want to declare " + name + " as the winner?\nNOTE: Fees may apply when declaring the winner. Do not repeat or you may lose fees.")) {
    simplewager.endWager(left, function(error, result) {
      if (!error) {
        alert(name + " has been declared as the winner!");
        refreshApp(result);
      }
      else {
        alert("Somethins is wrong. Please try again later.");
        console.log(error);
      }
    });
  }
}

function doDeclareWinner(left) {
  if (left) {
    simplewager.leftBetName(function(error, name) {
      declareWinnerWithName(left, name);
    });
  }
  else {
    simplewager.rightBetName(function(error, name) {
      declareWinnerWithName(left, name);
    });
  }
}

function declareWinner(left) {
  checkNetwork(function() {
    simplewager.house(function(error, house) {
      if(!error) {
        if (house == web3js.eth.defaultAccount) {
          doDeclareWinner(left);
        }
        else {
          alert("Only house can decalre the winner.");
        }
      }
      else {
        console.log(error);
      }
    });
  });
}

function refreshApp(transaction) {
  document.getElementById('leftamount').innerHTML = "...Refreshing...";
  document.getElementById('leftplacebet').innerHTML = "";
  document.getElementById('leftwinning').innerHTML = "";
  document.getElementById('rightamount').innerHTML = "...Refreshing...";
  document.getElementById('rightplacebet').innerHTML = "";
  document.getElementById('rightwinning').innerHTML = "";

  simplewager.house(function(error, house) {
    if (!error && house == web3js.eth.defaultAccount) {
      document.getElementById('claimhousecut').innerHTML = "...Refreshing...";
      document.getElementById('housecutamount').innerHTML = "";
    }
  });

  var filter = web3js.eth.filter({ fromBlock: 'latest', toBlock: 'latest', address: web3js.eth.defaultAddress});

  filter.watch(function(error, result) {
    if (!error) {
      if (result.transactionHash == transaction) {
        filter.stopWatching();
        runApp();

        if (result.removed) {
          alert("The transaction did not complete. Please try again later.");
        }
      }
    }
    else {
      console.log(error);
      filter.stopWatching();
    }
  });
}

function placeBetWithAddress(left, name, address) {
  simplewager.minBet(function(error, result) {
    var minBet = web3js.fromWei(result, 'ether');
    var prompt = "You are about to place bet on " + name + ".\nThe minimum bet is " + minBet + " ETH.\nPlease enter your bet:";
    var bet = window.prompt(prompt, minBet);
    if (bet === null) {
      return;
    }
    else if (bet === '') {
      alert("You need to enter something to place bet.");
    }

    var amount = web3js.toWei(bet, 'ether');
    if (confirm("Bet on " + name + " with " + bet + " ETH?")) {
      simplewager.placeBet.sendTransaction(left, { from: address, value: amount }, function(error, result) {
        if (!error) {
          alert("You have placed the bet! It will take some time for the bet to reflect on the blockchain.");
          // refresh the page
          //console.log(result);
          refreshApp(result);
        }
        else {
          alert("Something is wrong, please try again.");
          console.log(error);
        }
      });
    }
  });
}

function placeBet(left) {
  checkNetwork(function() {
    var address = web3js.eth.defaultAccount;
    simplewager.house(function(error, result) {
      if (address == result) {
        alert("House can not place bet.");
      }
      else {
        if (left) {
          simplewager.leftBetName(function(error, name) {
            placeBetWithAddress(left, name, address);
          });
        }
        else {
          simplewager.rightBetName(function(error, name) {
            placeBetWithAddress(left, name, address);
          });
        }
      }
    });
  });
}

function runApp() {
  checkNetwork(function() {
    simplewager.name(function(error, name) {
      document.getElementById('wagername').innerHTML = name;
    });
    simplewager.leftBetName(function(error, leftBetName) {
      document.getElementById('leftbet').innerHTML = leftBetName;
    });
    simplewager.leftTotalBettingAmount(function(error, leftAmount) {
      document.getElementById('leftamount').innerHTML = web3js.fromWei(leftAmount, 'ether') + " ETH";
    });
    simplewager.rightBetName(function(error, rightBetName) {
      document.getElementById('rightbet').innerHTML = rightBetName;
    });
    simplewager.rightTotalBettingAmount(function(error, rightAmount) {
      document.getElementById('rightamount').innerHTML = web3js.fromWei(rightAmount, 'ether') + " ETH";
    });

    document.getElementById('leftwon').innerHTML = '';
    document.getElementById('rightwon').innerHTML = '';

    document.getElementById('leftplacebet').innerHTML = '';
    document.getElementById('rightplacebet').innerHTML = '';

    document.getElementById('leftwinning').innerHTML = '';
    document.getElementById('rightwinning').innerHTML = '';

    document.getElementById('claimhousecut').innerHTML = '';
    document.getElementById('housecutamount').innerHTML = '';

    simplewager.ended(function(error, ended) {
      if (ended) {
        // wager ended
        simplewager.house(function(error, house) {
          if(!error) {
            simplewager.leftWonTheWager(function(error, leftWon) {
              if (!error) {
                document.getElementById('leftwon').innerHTML = leftWon ? 'WON!' : '';
                document.getElementById('rightwon').innerHTML = leftWon ? '' : 'WON!';

                if (house == web3js.eth.defaultAccount) {
                  // show house UI
                  simplewager.houseCutAlreadyClaimed(function(error, result) {
                    if (!error) {
                      if (result) {
                        document.getElementById('claimhousecut').innerHTML = 'House cut was already claimed.';
                      }
                      else {
                        document.getElementById('claimhousecut').innerHTML = '<button type="button" onclick="claimHouseCut()">Claim House Cut</button>';
                        simplewager.totalBettingAmount(function(error, totalBettingAmount) {
                          if(!error) {
                            simplewager.totalBettingAmountMinusHouseCut(function(error, totalBettingAmountMinusHouseCut) {
                              if (!error) {
                                var houseCutAmount = totalBettingAmount.minus(totalBettingAmountMinusHouseCut);
                                document.getElementById('housecutamount').innerHTML = 'House Cut is ' + web3js.fromWei(houseCutAmount, 'ether') + ' ETH (fees may apply)';
                              }
                            });
                          }
                        });
                      }
                    }
                    else {
                      console.log(error);
                    }
                  });
                }
                else {
                  // check if this account is elegible
                  var address = web3js.eth.defaultAccount;
                  simplewager.totalBettingAmountMinusHouseCut(function(error, totalBettingAmountMinusHouseCut) {
                    if (!error) {
                      if (leftWon) {
                        simplewager.leftTotalBettingAmount(function(error, leftTotalBettingAmount) {
                          if (!error) {
                            simplewager.leftBettingAmount(address, function(error, result) {
                              if (!error && !result.isZero()) {
                                simplewager.winningAlreadyClaimed(address, function(error, claimed) {
                                  if (!error && !claimed) {
                                    document.getElementById('leftplacebet').innerHTML = '<button type="button" onclick="claim(true)">Claim Winnings</button>';
                                    var estimatedProceeding = result.mul(totalBettingAmountMinusHouseCut).divToInt(leftTotalBettingAmount);
                                    document.getElementById('leftwinning').innerHTML = 'Your estimated winning is ' + web3js.fromWei(estimatedProceeding) + ' ETH (fees may apply)';
                                  }
                                });
                              }
                            });
                          }
                        });
                      }
                      else {
                        simplewager.rightTotalBettingAmount(function(error, leftTotalBettingAmount) {
                          if (!error) {
                            simplewager.leftBettingAmount(address, function(error, result) {
                              if (!error && !result.isZero()) {
                                simplewager.winningAlreadyClaimed(address, function(error, claimed) {
                                  if (!error && !claimed) {
                                    document.getElementById('rightplacebet').innerHTML = '<button type="button" onclick="claim(false)">Claim Winnings</button>';
                                    var estimatedProceeding = result.mul(totalBettingAmountMinusHouseCut).divToInt(leftTotalBettingAmount);
                                    document.getElementById('rightwinning').innerHTML = 'Your estimated winning is ' + web3js.fromWei(estimatedProceeding) + ' ETH (fees may apply)';
                                  }
                                });
                              }
                            });
                          }
                        });
                      }
                    }
                  });
                }
              }
              else {
                console.log(error);
              }
            });

            simplewager.wagerEnd(function(error, wagerEnd) {
              if (!error) {
                var endTime = new Date(wagerEnd * 1000);
                document.getElementById('endtime').innerHTML = 'Bet ended at ' + endTime.toString();
              }
              else {
                console.log(error);
              }
            });
          }
          else {
            console.log(error);
          }
        });
      }
      else {
        simplewager.house(function(error, house) {
          if (!error) {
            simplewager.wagerEnd(function(error, wagerEnd) {
              var endTime = new Date(wagerEnd * 1000);
              
              if (house == web3js.eth.defaultAccount) {
                // house account
                if (Date.now() > endTime) {
                  document.getElementById('endtime').innerHTML = 'Bet ended at ' + endTime.toString() + ".";
                  document.getElementById('leftplacebet').innerHTML = '<button type="button" onclick="declareWinner(true)">Declare this side won</button>';
                  document.getElementById('rightplacebet').innerHTML = '<button type="button" onclick="declareWinner(false)">Declare this side won</button>';
                }
                else {
                  document.getElementById('endtime').innerHTML = 'Bet will end at ' + endTime.toString();
                  document.getElementById('leftplacebet').innerHTML = '';
                  document.getElementById('rightplacebet').innerHTML = '';
                }
              }
              else {
                if (Date.now() > endTime) {
                  document.getElementById('endtime').innerHTML = 'Bet ended at ' + endTime.toString() + ", please wait for the result.";
                  document.getElementById('leftplacebet').innerHTML = 'Wager Ended';
                  document.getElementById('rightplacebet').innerHTML = 'Wager Ended';
                }
                else {
                  document.getElementById('endtime').innerHTML = 'Bet will end at ' + endTime.toString();
                  document.getElementById('leftplacebet').innerHTML = '<button type="button" onclick="placeBet(true)">Place Bet</button>';
                  document.getElementById('rightplacebet').innerHTML = '<button type="button" onclick="placeBet(false)">Place Bet</button>';
                }
              }
            });
          }
          else {
            console.log(error);
          }
        });
      }
    });

    simplewager.houseCut(function(error, houseCut) {
      document.getElementById('housecut').innerHTML = 'House Cut: ' + houseCut + '%';
    });
  });
}

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

function startApp() {
  console.log("StartApp");
  runApp();

  var account = web3js.eth.defaultAccount;
  var accountInterval = setInterval(function() {
    if (web3js.eth.defaultAccount !== account) {
      account = web3js.eth.defaultAccount;
      runApp();
    }
  }, 100);
}

window.addEventListener('load', function() {
  // Checking if Web3 has been injected by the browser (Mist/MetaMask)
  if (typeof web3 !== 'undefined') {
    // Use Mist/MetaMask's provider
    web3js = new Web3(web3.currentProvider);
    var contractAddr = "<?php echo $contract; ?>";
    simplewager = web3js.eth.contract(contractABI).at(contractAddr);
    startApp();
  } else {
    console.log('No web3? You should consider trying MetaMask!')
    // fallback - use your fallback strategy (local node / hosted node + in-dapp id mgmt / fail)
    document.getElementById('metamask').innerHTML = '<a href="https://metamask.io"><img src="download-metamask.png"></a>';
  }
})
</script>
