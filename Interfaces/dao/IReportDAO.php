<?php


/**
 *
 * @author jorge
 */
interface IReportDAO extends Idao_interface{
    public function checkSolved($idReport, $unsolved = -1);
}
