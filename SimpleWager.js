var contractABI = [{"constant":true,"inputs":[],"name":"name","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"left","type":"bool"}],"name":"placeBet","outputs":[],"payable":true,"stateMutability":"payable","type":"function"},{"constant":true,"inputs":[],"name":"rightBetName","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"ended","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"houseCut","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"leftWonTheWager","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[],"name":"claim","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[],"name":"claimHouseCut","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"houseCutAlreadyClaimed","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"leftTotalBettingAmount","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"minBet","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"wagerEnd","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"","type":"address"}],"name":"leftBettingAmount","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"totalBettingAmount","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"leftBetName","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"","type":"address"}],"name":"rightBettingAmount","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_leftWon","type":"bool"}],"name":"endWager","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"","type":"address"}],"name":"winningAlreadyClaimed","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"totalBettingAmountMinusHouseCut","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"rightTotalBettingAmount","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"house","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"inputs":[{"name":"_wagerName","type":"string"},{"name":"_leftBetName","type":"string"},{"name":"_rightBetName","type":"string"},{"name":"_activeTime","type":"uint256"},{"name":"_minBet","type":"uint256"},{"name":"_houseCut","type":"uint256"}],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":false,"name":"betLeft","type":"bool"},{"indexed":false,"name":"amount","type":"uint256"}],"name":"BetPlaced","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"name":"leftWon","type":"bool"}],"name":"WagerEnded","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"name":"amount","type":"uint256"}],"name":"WinningClaimed","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"name":"amount","type":"uint256"}],"name":"HouseCutClaimed","type":"event"}];
var contractCode = '0x60806040523480156200001157600080fd5b5060405162001993380380620019938339810180604052810190808051820192919060200180518201929190602001805182019291906020018051906020019092919080519060200190929190805190602001909291905050506064811015156200010a576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004018080602001828103825260218152602001807f486f75736520637574206d757374206265206c657373207468616e203130302581526020017f2e0000000000000000000000000000000000000000000000000000000000000081525060400191505060405180910390fd5b33600360006101000a81548173ffffffffffffffffffffffffffffffffffffffff021916908373ffffffffffffffffffffffffffffffffffffffff160217905550856000908051906020019062000163929190620001b9565b5084600190805190602001906200017c929190620001b9565b50836002908051906020019062000195929190620001b9565b50824201600481905550816005819055508060068190555050505050505062000268565b828054600181600116156101000203166002900490600052602060002090601f016020900481019282601f10620001fc57805160ff19168380011785556200022d565b828001600101855582156200022d579182015b828111156200022c5782518255916020019190600101906200020f565b5b5090506200023c919062000240565b5090565b6200026591905b808211156200026157600081600090555060010162000247565b5090565b90565b61171b80620002786000396000f30060806040526004361061011d576000357c0100000000000000000000000000000000000000000000000000000000900463ffffffff16806306fdde0314610122578063096a3778146101b2578063108ea186146101d457806312fa6feb1461026457806325c98624146102935780634803f7df146102be5780634e71d92d146102ed57806370a2355d1461031c5780638eb91b341461034b578063961290391461037a5780639619367d146103a557806398e58145146103d0578063bbb99c49146103fb578063cc70cd4214610452578063ccba08721461047d578063d1cc4c1a1461050d578063de124c9814610564578063e53ba59f14610593578063ead61dde146105ee578063eec41ae714610619578063ff9b3acf14610644575b600080fd5b34801561012e57600080fd5b5061013761069b565b6040518080602001828103825283818151815260200191508051906020019080838360005b8381101561017757808201518184015260208101905061015c565b50505050905090810190601f1680156101a45780820380516001836020036101000a031916815260200191505b509250505060405180910390f35b6101d2600480360381019080803515159060200190929190505050610739565b005b3480156101e057600080fd5b506101e9610a3f565b6040518080602001828103825283818151815260200191508051906020019080838360005b8381101561022957808201518184015260208101905061020e565b50505050905090810190601f1680156102565780820380516001836020036101000a031916815260200191505b509250505060405180910390f35b34801561027057600080fd5b50610279610add565b604051808215151515815260200191505060405180910390f35b34801561029f57600080fd5b506102a8610af0565b6040518082815260200191505060405180910390f35b3480156102ca57600080fd5b506102d3610af6565b604051808215151515815260200191505060405180910390f35b3480156102f957600080fd5b50610302610b09565b604051808215151515815260200191505060405180910390f35b34801561032857600080fd5b50610331610f72565b604051808215151515815260200191505060405180910390f35b34801561035757600080fd5b5061036061122c565b604051808215151515815260200191505060405180910390f35b34801561038657600080fd5b5061038f61123f565b6040518082815260200191505060405180910390f35b3480156103b157600080fd5b506103ba611245565b6040518082815260200191505060405180910390f35b3480156103dc57600080fd5b506103e561124b565b6040518082815260200191505060405180910390f35b34801561040757600080fd5b5061043c600480360381019080803573ffffffffffffffffffffffffffffffffffffffff169060200190929190505050611251565b6040518082815260200191505060405180910390f35b34801561045e57600080fd5b50610467611269565b6040518082815260200191505060405180910390f35b34801561048957600080fd5b5061049261126f565b6040518080602001828103825283818151815260200191508051906020019080838360005b838110156104d25780820151818401526020810190506104b7565b50505050905090810190601f1680156104ff5780820380516001836020036101000a031916815260200191505b509250505060405180910390f35b34801561051957600080fd5b5061054e600480360381019080803573ffffffffffffffffffffffffffffffffffffffff16906020019092919050505061130d565b6040518082815260200191505060405180910390f35b34801561057057600080fd5b50610591600480360381019080803515159060200190929190505050611325565b005b34801561059f57600080fd5b506105d4600480360381019080803573ffffffffffffffffffffffffffffffffffffffff16906020019092919050505061169d565b604051808215151515815260200191505060405180910390f35b3480156105fa57600080fd5b506106036116bd565b6040518082815260200191505060405180910390f35b34801561062557600080fd5b5061062e6116c3565b6040518082815260200191505060405180910390f35b34801561065057600080fd5b506106596116c9565b604051808273ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200191505060405180910390f35b60008054600181600116156101000203166002900480601f0160208091040260200160405190810160405280929190818152602001828054600181600116156101000203166002900480156107315780601f1061070657610100808354040283529160200191610731565b820191906000526020600020905b81548152906001019060200180831161071457829003601f168201915b505050505081565b60045442111515156107b3576040517f08c379a000000000000000000000000000000000000000000000000000000000815260040180806020018281038252600c8152602001807f576167657220656e6465642e000000000000000000000000000000000000000081525060200191505060405180910390fd5b600554341015151561082d576040517f08c379a000000000000000000000000000000000000000000000000000000000815260040180806020018281038252601e8152602001807f426574206d75737420626520686967686572207468616e206d696e426574000081525060200191505060405180910390fd5b600360009054906101000a900473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff163373ffffffffffffffffffffffffffffffffffffffff1614151515610919576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004018080602001828103825260218152602001807f486f757365206973206e6f7420616c6c6f77656420746f20706c61636520626581526020017f740000000000000000000000000000000000000000000000000000000000000081525060400191505060405180910390fd5b6000341115610a3c57801561098a5734600b60003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002060008282540192505081905550346007600082825401925050819055506109e8565b34600c60003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002060008282540192505081905550346008600082825401925050819055505b346009600082825401925050819055507f2e610aaeb5b17dcc442c8b403a1e2e20228dfa0c359dbe1fe35e1af914eda117813460405180831515151581526020018281526020019250505060405180910390a15b50565b60028054600181600116156101000203166002900480601f016020809104026020016040519081016040528092919081815260200182805460018160011615610100020316600290048015610ad55780601f10610aaa57610100808354040283529160200191610ad5565b820191906000526020600020905b815481529060010190602001808311610ab857829003601f168201915b505050505081565b600e60009054906101000a900460ff1681565b60065481565b600e60019054906101000a900460ff1681565b6000806000600e60009054906101000a900460ff161515610b92576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004018080602001828103825260168152602001807f5761676572206973207374696c6c206f6e676f696e670000000000000000000081525060200191505060405180910390fd5b600e60019054906101000a900460ff16610bee576000600c60003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020016000205411610c32565b6000600b60003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002054115b1515610ca6576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004018080602001828103825260148152602001807f4e6f2077696e6e696e67207265636f726465642e00000000000000000000000081525060200191505060405180910390fd5b600d60003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002060009054906101000a900460ff16151515610d68576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004018080602001828103825260188152602001807f57696e6e696e6720616c726561647920636c61696d65642e000000000000000081525060200191505060405180910390fd5b6001600d60003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002060006101000a81548160ff0219169083151502179055506000915060009050600e60019054906101000a900460ff1615610e2957600b60003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020016000205491506007549050610e71565b600c60003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002054915060085490505b80600a548302811515610e8057fe5b0491506000821415610e955760019250610f6d565b3373ffffffffffffffffffffffffffffffffffffffff166108fc839081150290604051600060405180830381858888f193505050501515610f31576000600d60003373ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff16815260200190815260200160002060006101000a81548160ff02191690831515021790555060009250610f6d565b7fc695b32a7a95acb7dbfcaa22c7a4a8704eb5814731c1c8034a865d5371f87475826040518082815260200191505060405180910390a1600192505b505090565b6000803373ffffffffffffffffffffffffffffffffffffffff16600360009054906101000a900473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1614151561103a576040517f08c379a000000000000000000000000000000000000000000000000000000000815260040180806020018281038252601f8152602001807f4f6e6c7920686f7573652063616e20636c61696d20686f757365206375742e0081525060200191505060405180910390fd5b600e60009054906101000a900460ff1615156110be576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004018080602001828103825260178152602001807f5761676572206973206e6f7420636f6e636c756465642e00000000000000000081525060200191505060405180910390fd5b600e60029054906101000a900460ff16151515611169576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004018080602001828103825260238152602001807f486f757365206375742068617320616c7265616479206265656e20636c61696d81526020017f65642e000000000000000000000000000000000000000000000000000000000081525060400191505060405180910390fd5b6001600e60026101000a81548160ff021916908315150217905550600a546009540390503373ffffffffffffffffffffffffffffffffffffffff166108fc829081150290604051600060405180830381858888f1935050505015156111ec576000600e60026101000a81548160ff02191690831515021790555060009150611228565b7f43248a83b2082086a2221c6f9dbbecf8292690b8294f8f9fbbbde8787092eaf2816040518082815260200191505060405180910390a1600191505b5090565b600e60029054906101000a900460ff1681565b60075481565b60055481565b60045481565b600b6020528060005260406000206000915090505481565b60095481565b60018054600181600116156101000203166002900480601f0160208091040260200160405190810160405280929190818152602001828054600181600116156101000203166002900480156113055780601f106112da57610100808354040283529160200191611305565b820191906000526020600020905b8154815290600101906020018083116112e857829003601f168201915b505050505081565b600c6020528060005260406000206000915090505481565b3373ffffffffffffffffffffffffffffffffffffffff16600360009054906101000a900473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff161415156113ea576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004018080602001828103825260198152602001807f4f6e6c7920686f7573652063616e20656e642077616765722e0000000000000081525060200191505060405180910390fd5b60045442111515611463576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004018080602001828103825260168152602001807f5761676572206973207374696c6c206163746976652e0000000000000000000081525060200191505060405180910390fd5b600e60009054906101000a900460ff1615151561150e576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004018080602001828103825260218152602001807f656e6457616765722068617320616c7265616479206265656e2063616c6c656481526020017f2e0000000000000000000000000000000000000000000000000000000000000081525060400191505060405180910390fd5b801561159357600060075411151561158e576040517f08c379a000000000000000000000000000000000000000000000000000000000815260040180806020018281038252601a8152602001807f57696e6e696e672073696465206d75737420686173206265742e00000000000081525060200191505060405180910390fd5b61160e565b600060085411151561160d576040517f08c379a000000000000000000000000000000000000000000000000000000000815260040180806020018281038252601a8152602001807f57696e6e696e672073696465206d75737420686173206265742e00000000000081525060200191505060405180910390fd5b5b6001600e60006101000a81548160ff02191690831515021790555080600e60016101000a81548160ff02191690831515021790555060646006546064036009540281151561165857fe5b04600a819055507fe1aa90b24ed686a13f919b354a694c1ac99667b064282f9b85f378824553c37381604051808215151515815260200191505060405180910390a150565b600d6020528060005260406000206000915054906101000a900460ff1681565b600a5481565b60085481565b600360009054906101000a900473ffffffffffffffffffffffffffffffffffffffff16815600a165627a7a7230582013aa2455c36629a5ebf4456e996fb541e06ea32f61d616fbbcdbb3ba9e841e540029';
