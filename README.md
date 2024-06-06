## PHP script for API request to fetch database data

<p>The <code>index.php</code> script is a PHP file acting as the starting point for making API requests. It initiates a cURL session to send a POST request to <code>endpoint.php</code>, which is expected to return a JSON response. The main objective is to fetch data from the database using a provided API key and display it on the webpage.</p>
<h3>Explanation of <code>index.php</code>:</h3>
<ol>
<li>
<p><strong>URL and Headers</strong>:</p>
<ul>
<li>Defines the URL (<code>endpoint.php</code>) where the POST request will be sent.</li>
<li>Sets a variable for the API key.</li>
</ul>
</li>
<li>
<p><strong>Preparation of POST Data</strong>:</p>
<ul>
<li>Prepares the POST data, including the API key.</li>
</ul>
</li>
<li>
<p><strong>Initialization and Configuration of cURL</strong>:</p>
<ul>
<li>Initializes a cURL session.</li>
<li>Configures various options such as returning the transfer as a string, enabling the POST method, and setting the POST fields.</li>
</ul>
</li>
<li>
<p><strong>Execution of the cURL Session</strong>:</p>
<ul>
<li>Executes the cURL session and receives the response.</li>
</ul>
</li>
<li>
<p><strong>Handling the Response</strong>:</p>
<ul>
<li>Checks for any cURL errors.</li>
<li>On success, parses the JSON response.</li>
<li>Processes the response data, checks if the status is 'success' or 'error', and displays the data or error message accordingly.</li>
</ul>
</li>
<li>
<p><strong>Closing of the cURL Session</strong>:</p>
<ul>
<li>Closes the cURL session.</li>
</ul>
</li>
</ol>
<p><code>endpoint.php</code> is another PHP file that serves as the endpoint for API requests. It handles database interactions and returns responses in JSON format.</p>
<h3>Detailed Explanation of <code>endpoint.php</code>:</h3>
<ol>
<li>
<p><strong>Database Connection</strong>:</p>
<ul>
<li>Establishes a connection to the database using mysqli.</li>
</ul>
</li>
<li>
<p><strong>Table Creation</strong>:</p>
<ul>
<li>Checks whether the necessary tables (<code>api_keys</code> and <code>data</code>) exist in the database and creates them if needed.</li>
<li>The <code>api_keys</code> table stores API keys, while the <code>data</code> table holds the actual data.</li>
</ul>
</li>
<li>
<p><strong>Insertion of Default Values</strong>:</p>
<ul>
<li>If the tables are empty, inserts default values (a default API key in the <code>api_keys</code> table and a default data entry in the <code>data</code> table).</li>
</ul>
</li>
<li>
<p><strong>Validation of API Key</strong>:</p>
<ul>
<li>Retrieves expected API keys from the database and validates the received API key.</li>
<li>If the received API key is valid, fetches the corresponding data from the <code>data</code> table and sends it as part of the response.</li>
</ul>
</li>
<li>
<p><strong>Request Type and Error Handling</strong>:</p>
<ul>
<li>Checks the request type (POST or GET) and handles invalid requests.</li>
<li>If the request is invalid or the API key is invalid, sends an appropriate error message.</li>
</ul>
</li>
<li>
<p><strong>Closing the Database Connection</strong>:</p>
<ul>
<li>Closes the database connection.</li>
</ul>
</li>
<li>
<p><strong>Response</strong>:</p>
<ul>
<li>Sends the response back in JSON format with status and message fields, along with optional data if applicable.</li>
</ul>
</li>
</ol>
<p>Overall, <code>endpoint.php</code> functions as the backend logic for handling API requests and communicating with the database, while <code>index.php</code> is responsible for making requests to <code>endpoint.php</code> and displaying the retrieved data or error messages.</p>
