<?php


namespace Payment\System\App\Services\External;


use Payment\System\App\Services\External\Payment\Exceptions\PaymentApiErrorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Response;
use RuntimeException;

class PaymentService
{
    const PAYPAL = 'paypal';
    const CAPITALIST = 'capitalist';
    const YANDEX = 'yandex';

    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELED = 'canceled';
    const STATUS_REFUND = 'refund';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CHARGEBACK = 'chargeback';

    private static $graphqlRouteUrl;
    private $requestClient;

    /**
     * @throws PaymentApiErrorException
     */
    public function getPlans()
    {
        $httpVerb = 'GET';
        $headers = [
            'Authorization' => 'Bearer ' . $this->getToken(),
        ];
        $query = 'query plans{ plans{id name type price currency}}';
        $response = $this->makeRequest($httpVerb, $headers, $query);
        return $response->data->plans;
    }

    /**
     * @param $subscription
     * @throws PaymentApiErrorException
     */
    public function getSubscription($subscription)
    {
        $httpVerb = 'GET';
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $query = 'query subscriptionDetails ($uuid: String!) {
             subscriptionDetails(uuid: $uuid){
                status, recurringCycle, recurringDate, transactions {id, status}
             }
         }';
        $variables = json_encode(['uuid' => $subscription->subscriptionId]);

        $response = $this->makeRequest($httpVerb, $headers, $query, $variables);

        return $response->data->subscriptionDetails;
    }

    /**
     * @param $invoice
     * @throws PaymentApiErrorException
     */
    public function getInvoice($invoice)
    {
        $httpVerb = 'GET';
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $query = 'query invoiceDetails ($uuid: String!) {
             invoiceDetails(uuid: $uuid){
                status
             }
         }';
        $variables = json_encode(['uuid' => $invoice->uuid]);

        $response = $this->makeRequest($httpVerb, $headers, $query, $variables);

        return $response->data->invoiceDetails;
    }

    /**
     * @param $uuid
     * @throws PaymentApiErrorException
     */
    public function getTransaction($uuid)
    {
        $httpVerb = 'GET';
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $query = 'query transactionDetails ($uuid: String!) {
             transactionDetails(id: $uuid){
                paymentPlatform, currency, id, price, status, subscription{id}, invoice{id}
             }
         }';
        $variables = json_encode(['uuid' => $uuid]);

        $response = $this->makeRequest($httpVerb, $headers, $query, $variables);

        return $response->data->transactionDetails;
    }

    /**
     * @param $planId
     * @param $paymentMethod
     * @return mixed
     * @throws PaymentApiErrorException
     */
    public function subscribe($planId, $paymentMethod)
    {
        $httpVerb = 'POST';
        $headers = [
            'Authorization' => 'Bearer ' . $this->getToken(),
        ];
        $mutation = 'mutation subscribe(
        $plan: String!, $paymentMethod: String!
        ){
            subscribe(
                plan: $plan, paymentMethod: $paymentMethod
                ){
                    id, approvalLink
                }
        }';
        $variables = json_encode(['plan' => $planId, 'paymentMethod' => $paymentMethod]);
        $response = $this->makeRequest($httpVerb, $headers, $mutation, $variables);

        return $response->data->subscribe;
    }

    /**
     * @param array $items
     * @return mixed
     * @throws PaymentApiErrorException
     */
    public function invoicing(array $items)
    {
        $httpVerb = 'POST';
        $headers = [
            'Authorization' => 'Bearer ' . $this->getToken(),
        ];
        $mutation = 'mutation invoicing($items: [InvoiceItemInput]!, $currency: String!) {
            invoicing(invoiceItems: $items, currency: $currency) {
                id,
                approvalLink
            }
        }';
        $variables = json_encode(['items' => $items, 'currency' => 'USD']);
        $response = $this->makeRequest($httpVerb, $headers, $mutation, $variables);

        return $response->data->invoicing;
    }

    /**
     * @param $httpVerb
     * @param $headers
     * @param $query
     * @param $variables
     * @return mixed
     * @throws PaymentApiErrorException
     */
    private function makeRequest($httpVerb, $headers, $query, $variables = '')
    {
        try {
            self::$graphqlRouteUrl = config('services.payment.url');
            $response = $this->getRequestClientInstance()->request($httpVerb, $this::$graphqlRouteUrl, [
                RequestOptions::HEADERS => $headers,
                RequestOptions::JSON => ['query' => $query, 'variables' => $variables],
            ]);
            $responseJson = json_decode($response->getBody()->getContents());
        } catch (GuzzleException $exception) {
            throw new PaymentApiErrorException($exception->getMessage());
        } catch (RuntimeException $exception) {
            throw new PaymentApiErrorException($exception->getMessage());
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new PaymentApiErrorException('Unauthorized.');
        }

        return $responseJson;
    }

    /**
     * @return Client
     */
    private function getRequestClientInstance()
    {
        if (!$this->requestClient) {
            $this->requestClient = new Client();
        }

        return $this->requestClient;
    }

    /**
     * @return mixed
     */
    private function getToken()
    {
        return config('services.payment.clientSecret');
    }
}
