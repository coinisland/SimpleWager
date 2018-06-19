pragma solidity 0.4.24;

/// @title Simple wager contract with a house using an ERC20 token
interface TokenERC20 {
    function transfer(address _to, uint256 _value) external;
    function transferFrom(address _from, address _to, uint256 _value) external returns(bool success);
}

contract SimpleWagerERC20 {
    string public name;
    string public leftBetName;
    string public rightBetName;

    address public tokenAddress;
    address public house;
    uint public wagerEnd;
    uint public minBet;
    uint public houseCut; // in percentage

    uint public leftTotalBettingAmount;
    uint public rightTotalBettingAmount;
    uint public totalBettingAmount;
    uint public totalBettingAmountMinusHouseCut;

    // current betting amounts for each bet and each player
    mapping(address => uint) public leftBettingAmount;
    mapping(address => uint) public rightBettingAmount;
    mapping(address => bool) public winningAlreadyClaimed;

    // wager ended
    bool public ended;
    bool public leftWonTheWager;
    bool public houseCutAlreadyClaimed;

    event BetPlaced(bool betLeft, uint amount);
    event WagerEnded(bool leftWon);
    event WinningClaimed(uint amount);
    event HouseCutClaimed(uint amount);

    // create a simple wager
    constructor(
        string _wagerName,          // name of the wager (e.g. Will it rain tomorrow?)
        string _leftBetName,        // name of the left bet (e.g. Yes)
        string _rightBetName,       // name of the right bet (e.g. No)
        uint _activeTime,           // active time of wager in seconds
        uint _minBet,               // minimum bet
        uint _houseCut,             // house cut in percentage (1 for 1%)
        address _tokenAddress       // address of the ERC20 to be used
    ) public {
        require(_houseCut < 100, "House cut must be less than 100%.");

        house = msg.sender;
        name = _wagerName;
        leftBetName = _leftBetName;
        rightBetName = _rightBetName;
        wagerEnd = now + _activeTime;
        minBet = _minBet;
        houseCut = _houseCut;
        tokenAddress = _tokenAddress;
    }

    // Use approveAndCall function to place bet
    function receiveApproval(address _from, uint256 _value, address _token, bytes _extraData) public {
        // allows placing bet only before wager ended
        require(
            now <= wagerEnd,
            "Wager ended."
        );

        require(
            _value >= minBet,
            "Bet must be higher than minBet"
        );

        require(
            _from != house,
            "House is not allowed to place bet"
        );

        require(
            _token == tokenAddress,
            "Incorrect token address"
        );

        bool left = (_extraData[0] != 0);
        TokenERC20 token = TokenERC20(tokenAddress);

        if (_value > 0) {
            if (token.transferFrom(_from, this, _value)) {
                if (left) {
                    leftBettingAmount[_from] += _value;
                    leftTotalBettingAmount += _value;
                }
                else {
                    rightBettingAmount[_from] += _value;
                    rightTotalBettingAmount += _value;
                }

                totalBettingAmount += _value;
                emit BetPlaced(left, _value);
            }
        }
    }
    
    // claim winning
    function claim() public returns (bool) {
        require(ended, "Wager is still ongoing");
        require(leftWonTheWager ? leftBettingAmount[msg.sender] > 0 : rightBettingAmount[msg.sender] > 0, "No winning recorded.");
        require(!winningAlreadyClaimed[msg.sender], "Winning already claimed.");
        
        winningAlreadyClaimed[msg.sender] = true;
        uint amount = 0;
        uint totalAmount = 0;
        if (leftWonTheWager) {
            amount = leftBettingAmount[msg.sender];
            totalAmount = leftTotalBettingAmount;
        }
        else {
            amount = rightBettingAmount[msg.sender];
            totalAmount = rightTotalBettingAmount;
        }

        amount = amount * totalBettingAmountMinusHouseCut / totalAmount;
        if (amount == 0) {
            return true;
        }

        TokenERC20 token = TokenERC20(tokenAddress);
        token.transfer(msg.sender, amount);

        emit WinningClaimed(amount);

        return true;
    }

    // claim house cut
    function claimHouseCut() public returns (bool) {
        require(house == msg.sender, "Only house can claim house cut.");
        require(ended, "Wager is not concluded.");
        require(!houseCutAlreadyClaimed, "House cut has already been claimed.");

        // transfer house cut
        houseCutAlreadyClaimed = true;
        uint amount = totalBettingAmount - totalBettingAmountMinusHouseCut;

        TokenERC20 token = TokenERC20(tokenAddress);
        token.transfer(msg.sender, amount);

        emit HouseCutClaimed(amount);

        return true;
    }

    // end of wager and assign the winner (left or right)
    function endWager(bool _leftWon) public {
        // end wager with winner
        require(house == msg.sender, "Only house can end wager.");
        require(now > wagerEnd, "Wager is still active.");
        require(!ended, "endWager has already been called.");
        
        if (_leftWon) {
            require(leftTotalBettingAmount > 0, "Winning side must has bet.");
        }
        else {
            require(rightTotalBettingAmount > 0, "Winning side must has bet.");
        }

        // set end parameters
        ended = true;
        leftWonTheWager = _leftWon;
        totalBettingAmountMinusHouseCut = totalBettingAmount * (100 - houseCut) / 100;
 
        emit WagerEnded(_leftWon);
    }
}
