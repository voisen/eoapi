<?php
/**
 * @name eoapi open source，eoapi开源版本
 * @link https://www.eoapi.cn
 * @package eoapi
 * @author www.eoapi.cn 深圳波纹聚联网络科技有限公司 ©2015-2016

 * eoapi，业内领先的Api接口管理及测试平台，为您提供最专业便捷的在线接口管理、测试、维护以及各类性能测试方案，帮助您高效开发、安全协作。
 * 如在使用的过程中有任何问题，欢迎加入用户讨论群进行反馈，我们将会以最快的速度，最好的服务态度为您解决问题。
 * 用户讨论QQ群：284421832
 *
 * 注意！eoapi开源版本仅供用户下载试用、学习和交流，禁止“一切公开使用于商业用途”或者“以eoapi开源版本为基础而开发的二次版本”在互联网上流通。
 * 注意！一经发现，我们将立刻启用法律程序进行维权。
 * 再次感谢您的使用，希望我们能够共同维护国内的互联网开源文明和正常商业秩序。
 *
 */
class UserController
{
	// 返回json类型
	private $returnJson = array('type' => 'user');
	public function __construct()
	{
		// 身份验证
		$server = new GuestModule;
		if (!$server -> checkLogin())
		{
			$this -> returnJson['statusCode'] = '120005';
			exitOutput($this -> returnJson);
		}
	}

	/**
	 * 退出登录
	 */
	public function logout()
	{
		@session_start();
		@session_destroy();
		$this -> returnJson['statusCode'] = '000000';
		exitOutput(json_encode($this -> returnJson));
	}

	/**
	 * 修改密码
	 */
	public function changePassword()
	{
		$oldPassword = securelyInput('oldPassword');
		$newPassword = securelyInput('newPassword');

		if (!preg_match('/^[0-9a-zA-Z]{32}$/', $newPassword) || !preg_match('/^[0-9a-zA-Z]{32}$/', $oldPassword))
		{
			//密码非法
			$this -> returnJson['statusCode'] = '130002';
		}
		elseif ($oldPassword == $newPassword)
		{
			//密码相同
			$this -> returnJson['statusCode'] = '000000';
		}
		else
		{
			$server = new UserModule;
			$result = $server -> changePassword($oldPassword, $newPassword);

			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				$this -> returnJson['statusCode'] = '130006';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 修改昵称
	 */
	public function changeNickName()
	{
		$nickNameLength = mb_strlen(quickInput('nickName'), 'utf8');
		$nickName = securelyInput('nickName');

		if ($nickNameLength > 20)
		{
			//昵称格式非法
			$this -> returnJson['statusCode'] = '130008';
		}
		else
		{
			$server = new UserModule;
			$result = $server -> changeNickName($nickName);

			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				$this -> returnJson['statusCode'] = '130009';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 确认用户名
	 */
	public function confirmUserName()
	{
		$userName = securelyInput('userName');

		//验证用户名,4~16位非纯数字，英文数字下划线组合，只能以英文开头
		if (!preg_match('/^[a-zA-Z][0-9a-zA-Z_]{3,15}$/', $userName))
		{
			//用户名非法
			$this -> returnJson['statusCode'] = '130001';
		}
		else
		{
			$server = new UserModule;
			$result = $server -> confirmUserName($userName);

			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				$this -> returnJson['statusCode'] = '130010';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 获取用户信息
	 */
	public function getUserInfo()
	{
		$server = new UserModule;
		$result = $server -> getUserInfo($userToken);
		if ($result)
		{
			$this -> returnJson['statusCode'] = '000000';
			$this -> returnJson['userInfo'] = $result;
		}
		else
		{
			$this -> returnJson['statusCode'] = '130013';
		}
		exitOutput($this -> returnJson);
	}

}
?>