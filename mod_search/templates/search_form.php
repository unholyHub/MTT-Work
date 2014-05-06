<h1><?php echo locales::$text['header'] ?></h1>
<div>
    <form method="POST">
        <label>
            <?php echo locales::$text['from'] ?>
            <select name="search[from]">
                <?php echo $this->city->options_for_cities($_SESSION['locale'], $_POST['search']['from']) ?>
            </select>
        </label>

        <label>
            <?php echo locales::$text['to'] ?>
            <select name="search[to]">
                <?php echo $this->city->options_for_cities($_SESSION['locale'], $_POST['search']['to']) ?>
            </select>
        </label>
        <input type="submit" value=">>" />
    </form>
</div>

<div class="results">
    <?php if(!empty($this->results)): ?>
        
        <?php foreach($this->results as $result): ?>
            <table class="result">
            <tr>
                <th colspan="6">
                    <?php echo $result['destination']['from'] ?> - 
                    <?php echo $result['destination']['to'] ?>
                    (<?php echo $result['departs']['time'] ?> @ <?php echo $result['departs']['station'] ?>)
                </th>
            </tr>
            <tr>
                <th colspan="3"><?php echo locales::$text['departure'] ?></th>
                <th colspan="3"><?php echo locales::$text['arrival'] ?></th>
            </tr>
            <tr class="travel-data header">
                <td><?php echo locales::$text['day'] ?></td>
                <td><?php echo locales::$text['time'] ?></td>
                <td><?php echo locales::$text['station'] ?></td>
                <td><?php echo locales::$text['day'] ?></td>
                <td><?php echo locales::$text['time'] ?></td>
                <td><?php echo locales::$text['station'] ?></td>
            </tr>
            <?php foreach($result['departure_days'] as $key => $day): ?>
            <tr class="travel-data">
                <td><?php echo $day ?></td>
                <td><?php echo $result['departs']['time'] ?></td>
                <td><?php echo $result['departs']['station'] ?></td>
                <td><?php echo $result['arrival_days'][$key] ?></td>
                <td><?php echo $result['arrives']['time'] ?></td>
                <td><?php echo $result['arrives']['station'] ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="3"><?php echo locales::$text['price_oneway'] ?></th>
                <th colspan="3"><?php echo locales::$text['price_twoway'] ?></th>
            </tr>
            <tr class="travel-data center">
                <td colspan="3" align="right">
                    <div><?php echo $result['regular_promo_price']['oneway'] ?></div>
                </td>
                <td colspan="3" align="right">
                    <div><?php echo $result['regular_promo_price']['twoway'] ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="6" align="right">
                    <a href="<?php echo $result['buy_url'] ?>" target="_blank">
                        <button class="buy-button"><?php echo locales::$text['buy'] ?></button>
                    </a>
                </td>
            </tr>
            </table>
        <?php endforeach; ?>
        
    <?php endif; ?>
</div>
