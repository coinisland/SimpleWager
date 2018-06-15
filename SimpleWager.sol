pragma solidity 0.4.24;

/// @title Simple wager contract with a house
contract SimpleWager {
    string public name;
    string public leftBetName;
    string public rightBetName;

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
        uint _houseCut              // house cut in percentage (1 for 1%)
    ) public {
        require(_houseCut < 100, "House cut must be less than 100%.");

        house = msg.sender;
        name = _wagerName;
        leftBetName = _leftBetName;
        rightBetName = _rightBetName;
        wagerEnd = now + _activeTime;
        minBet = _minBet;
        houseCut = _houseCut;
    }

    // place bet for left or right
    function placeBet(bool left) public payable {
        // allows placing bet only before wager ended
        require(
            now <= wagerEnd,
            "Wager ended."
        );

        require(
            msg.value >= minBet,
            "Bet must be higher than minBet"
        );

        require(
            msg.sender != house,
            "House is not allowed to place bet"
        );

        if (msg.value > 0) {
            if (left) {
                leftBettingAmount[msg.sender] += msg.value;
                leftTotalBettingAmount += msg.value;
            }
            else {
                rightBettingAmount[msg.sender] += msg.value;
                rightTotalBettingAmount += msg.value;
            }

            totalBettingAmount += msg.value;
            emit BetPlaced(left, msg.value);
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
        
        if (!msg.sender.send(amount)) {
            winningAlreadyClaimed[msg.sender] = false;
            return false;
        }

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
        if (!msg.sender.send(amount)) {
            houseCutAlreadyClaimed = false;
            return false;
        }

        emit HouseCutClaimed(amount);

        return true;
    }

    // end of wager and assign the winner (left or right)
    function endWager(bool _leftWon) public {
        // end wager with winner
        require(house == msg.sender, "Only house can end wager.");
        require(now > wagerEnd, "Wager is still active.");
        require(!ended, "endWager has already been called.");

        // set end parameters
        ended = true;
        leftWonTheWager = _leftWon;
        totalBettingAmountMinusHouseCut = totalBettingAmount * (100 - houseCut) / 100;
 
        emit WagerEnded(_leftWon);
    }
}
