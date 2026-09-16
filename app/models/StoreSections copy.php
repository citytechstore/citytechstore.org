<?php
require_once('Database.php');
require('app/models/lib.php');


class StoreSection {
    private $DBM;

    public function __construct($DBM) {
        $this->DBM = $DBM;
    }

    // Check if the section already exists in the database
    public function section_exists_old($whereClause) {
        $stmt = $this->DBM->prepare("
            SELECT id FROM store_sections WHERE ".array_key_first($whereClause)." = ?
        ");
        $stmt->bind_param("s", $whereClause[0]);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }
    public function section_exists($whereClause) {
        // Get the first key (column name) and value from the whereClause array
        $column = array_key_first($whereClause);
        $value = $whereClause[$column];
        
        // Prepare the SQL statement with the column as the WHERE clause
        $stmt = $this->DBM->prepare("
            SELECT id FROM store_sections WHERE $column = ?
        ");
        
        // Bind the value to the prepared statement
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $stmt->store_result();
        
        // Check if the section exists
        $exists = $stmt->num_rows > 0;
        
        // Close the statement
        $stmt->close();
        
        return $exists;
    }
    
    // Create a new section
    public function create($section_data) {
        if ($this->section_exists(['section_name' => $section_data['section_name']])) {
            return 0;
        }
        if($this->DBM->array_insert("store_sections", $section_data)){
            return true;
        }
    }

    // Read a section by ID
    public function read_old($id) {
        $stmt = $this->DBM->prepare("SELECT * FROM store_sections WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function read($whereClause) {
        // Get the first key (column name) and value from the whereClause array
        $column = array_key_first($whereClause);
        $value = $whereClause[$column];
        
        // Prepare the SQL statement with the column as the WHERE clause
        $stmt = $this->DBM->prepare("
            SELECT * FROM store_sections WHERE $column = ?
        ");
        
        // Bind the value to the prepared statement
        $stmt->bind_param("s", $value);
        $stmt->execute();
        
        // Fetch the result as an associative array
        $result = $stmt->get_result()->fetch_assoc();
        
        // Close the statement
        $stmt->close();
        
        return $result;
    }
    public function read_all() {
        // Prepare the SQL statement to select all records from the store_sections table
        $stmt = $this->DBM->prepare("
            SELECT * FROM store_sections
        ");
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch all results as an associative array
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Close the statement
        $stmt->close();
        
        return $result;
    }
    
    // Update an existing section
    public function update($new_data, $whereClause) {
        // if (!$this->section_exists()) {
        //     return 0;
        // }
        return ($this->DBM->array_update("store_sections", $new_data, $whereClause)) ? true : false;
    }

    public function deleteById($id) {
        $stmt = $this->DBM->prepare("DELETE FROM store_sections WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }
    
    // Delete a section by ID
    public function delete($whereClause) {
        // Get the first key (column name) and value from the whereClause array
        $column = array_key_first($whereClause);
        $value = user_input_sanitize($whereClause[$column]);
        
        // Prepare the SQL statement with the column as the WHERE clause
        $stmt = $this->DBM->prepare("
            DELETE FROM store_sections WHERE $column = ?
        ");
        
        // Bind the value to the prepared statement
        $stmt->bind_param("s", $value);
        $stmt->execute();
        
        // Check if any rows were affected (i.e., deleted)
        $affected_rows = $stmt->affected_rows;
        
        // Close the statement
        $stmt->close();
        
        return $affected_rows > 0;
    }
    
}

$storeSection = new StoreSection($DatabaseModel);

class SectionView {
    private $DBM;

    public function __construct($DBM) {
        $this->DBM = $DBM;
    }

    // Method to retrieve all sections
    public function get_all_sections() {
        $stmt = $this->DBM->prepare("
            SELECT * FROM store_sections
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $sections = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $sections;
    }

    // Method to generate HTML view of sections
    public function generateHTMLView_old($sections_array) {
        if (empty($sections_array)) {
            return '<p class="text-center">No sections found.</p>';
        }

        $html = '<div class="container mt-3"><div class="row">';

        function prvActions($id, $placeholders){
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
                return '
                <a href="/storesection?render=edit&section_id=' . $id . '" class="btn btn-secondary">Edit</a>
                <a href="/storesection?render=delete&section_id=' . $id . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this section?\');">Delete</a>';
            }else{
                return '
                <a href="'.WA_LINK.'?text='.urlencode(replacePlaceholders(WA_SECTION_MESSAGE_TEXT, $placeholders)).'" class="btn btn-primary" target="_blank" rel="noopener noreferrer">Message on Whatsapp</a>
                ';
            }
        }
        foreach ($sections_array as $section) {
            $section_name = htmlspecialchars($section['section_name']);
            $section_image = htmlspecialchars($section['section_image']);
            $section_description = htmlspecialchars($section['section_description']);
            $section_short_description = htmlspecialchars($section['section_short_description']);
            $section_tags_description = htmlspecialchars($section['section_tags_description']);
            $placeholders = ['SECTION_ID' => $section['id'], 'SECTION_NAME' => $section['section_name']];
            $html .= '
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="' . $section_image . '" class="card-img-top" alt="' . ucfirst($section_name) . '">
                    <div class="card-body">
                        <h5 class="card-title">' . ucfirst($section_name) . '</h5>
                        <p class="card-text">' . ucfirst($section_description) . '</p>
                        <p class="card-text"><small class="text-muted">' . ucfirst($section_tags_description) . '</small></p>'.prvActions($section['id'], $placeholders).'
                    </div>
                </div>
            </div>';
        }

        $html .= '</div></div>';

        return $html;
    }

    public function generateHTMLView($sections_array) {
        // Start the Bootstrap list group
        $html = '<div class="list-group m-1 mt-3 mb-3">';
        $section_number = 1; // Initialize section counter
    
        // Loop through each section in the array
        foreach ($sections_array as $section) {
            $section_id = $section['id'];
            $section_name = $section['section_name'];
    
            // Create a list-group item for each section with section number
            $html .= '
            <a href="/storesection?render=sectionview&section_id=' . $section_id . '" class="list-group-item list-group-item-action">
                Section ' . $section_number . ' - ' . htmlspecialchars($section_name) . '
            </a>';
    
            // Increment the section number
            $section_number++;
        }
    
        // Close the Bootstrap list group
        $html .= '</div>';
    
        return $html;
    }

    public function generateSectionImageHTML($storeSection, $whereClause) {
        // Get section data by ID (assuming you have a method to fetch it)
        $section = $storeSection->read($whereClause);
        
        // Extract image path and section name
        $img_path = $section['section_image'];  // Path to the image
        $section_name = $section['section_name'];  // Section name
    
        // Generate the HTML output
        $html = '
        <div class="container text-center">
            <img src="' . htmlspecialchars($img_path) . '" class="img-fluid" alt="' . htmlspecialchars($section_name) . '">
        </div>';
    
        return $html;
    }
    
    
}



/**
 * Checks if the current URI matches the specified patterns.
 *
 * @return bool True if URI matches either pattern, false otherwise.
 */
function isUserAllowedToURI() {
    // Get the current URI
      // Get the current URI
      $uri = $_SERVER['REQUEST_URI'];
    
      // Parse the URI to get the query string
      $query = parse_url($uri, PHP_URL_QUERY);
      
      // Initialize the parameters array
      $params = [];
      
      // Check if the query string is not null
      if ($query !== null) {
          parse_str($query, $params);
      }
  
      // Check if the query parameters match either pattern
      if (
          (isset($params['render']) && $params['render'] === 'create') ||

          (isset($params['render']) && $params['render'] === 'edit' && isset($params['section_id'])) ||
          (isset($params['action']) && $params['action'] === 'delete' && isset($params['section_id']))
      ) {
          return true;
      }
      
      return false;
}

$sectionView = new SectionView($DatabaseModel);
$all_sections = $sectionView->get_all_sections();