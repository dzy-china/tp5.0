<?php

namespace app\my_api\behavior;

class MyResponse
{
    /**
     * 响应发送前的处理行为
     * @param mixed $params 传入的响应对象（通过引用传递，可直接修改）
     */
    public function run(&$response)
    {
        // 获取原始响应内容
        $originalContent = $response->getContent();

        // 获取HTTP状态码
        $httpCode = $response->getCode();

        // 定义状态码到消息的映射
        $statusMap = [
            200 => 'success',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            // 可以继续添加其他状态码映射
        ];

        // 默认消息
        $defaultMsg = 'Operation completed';

        // 确定消息内容
        $message = $statusMap[$httpCode] ?? $defaultMsg;

        /**
         * 处理原始内容：
         * 1. 尝试解码JSON内容（可能控制器已经返回了JSON字符串）
         * 2. 如果解码成功则使用解码后的数据，否则使用原始内容
         * 这样可以避免双重JSON编码导致的引号转义问题
         */
        $decodedContent = json_decode($originalContent, true); //json字符串转数组，如果解码失败，返回 null
        $data = $decodedContent!=null ? $decodedContent : $originalContent;

        // 构建标准化响应
        $standardizedResponse = [
            'code' => $httpCode,    // 使用HTTP状态码作为业务码
            'msg'  => $message,      // 根据状态码动态设置的消息
            'data' => $data,           // 原始响应数据
            'time' => date('Y-m-d H:i:s'), // 响应时间
        ];

        /**
         * 设置新响应内容：
         * 1. 使用json_encode进行编码
         * 2. JSON_UNESCAPED_UNICODE选项确保中文不被转为Unicode编码
         * 3. 注意这里只进行一次JSON编码
         */
        $response->content(
            json_encode($standardizedResponse, JSON_UNESCAPED_UNICODE)
        );

        // 确保响应头设置为JSON类型
        $response->header('Content-Type', 'application/json');
    }
}