<?php
class ModelCatalogQuantityapi extends Model {


public function getcheckModelQuery($implodeItemId){
  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE model = '" . $implodeItemId[0] . "'"); 

  return $query;
}


 public function updateCategory($categoryProducts,$product_id){

            $productsCategories = explode(',', $categoryProducts);

             $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

            foreach ($productsCategories as $category_id) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
            }
 }
 public function insertCategory($categoryProducts,$product_id){

             $productsCategories = explode(',', $categoryProducts);

            foreach ($productsCategories as $category_id) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
            }
}

public function insertManufacturer($manufacturerName){

          $query_manufacture = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $manufacturerName . "'"); 

                 
        if ($query_manufacture->num_rows) {

            $data_manufacture = $query_manufacture->row;
                  $manufacturer_id = $data_manufacture['manufacturer_id'];

           }else{

              $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer  SET name = '" . $this->db->escape($manufacturerName) . "', image = '', sort_order = '0'");

                $manufacturer_id = $this->db->getLastId();

            }


            return $manufacturer_id;
}


 public function addManufacturer($manufacturerName){

                 $query_manufacture = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $manufacturerName . "'"); 

                      if ($query_manufacture->num_rows) {

                           $data_manufacture = $query_manufacture->row;


                      if($data_manufacture['name'] == $manufacturerName) {

                             $this->db->query("UPDATE " . DB_PREFIX . "manufacturer  SET name = '" . $this->db->escape($manufacturerName) . "' WHERE manufacturer_id = '" . $data_manufacture['manufacturer_id']  . "'");

                      }else
                      {

                             $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer  SET name = '" . $this->db->escape($manufacturerName) . "', image = '', sort_order = '0'");

                             $manufacturer_id = $this->db->getLastId();

                             $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store  SET   manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '0'");


                      }

                      }else
                      {

                              $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer  SET name = '" . $this->db->escape($manufacturerName) . "', image = '', sort_order = '0'");

                              $manufacturer_id = $this->db->getLastId();

                              $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store  SET   manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '0'");

                          

                       }

  }


 public function updateData($dataImport = array()) {

      $imagePath = 'catalog/demo/';   

      $data_model = $dataImport['query']->row; 
      $implode = explode(',', $dataImport['quantitesApi']);
      $key = $implode[0];
      
      if($key < 10)
      {
      
         if($data_model['model'] == $dataImport['implodeItemId'][0]) {

             $this->db->query("UPDATE " . DB_PREFIX . "product SET height = '" . $dataImport['height']  . "', width = '" . $dataImport['width']  . "', upc = '" . $dataImport['upc'] ."' , model = '" . ltrim($dataImport['implodeItemId'][0],'0') . "', manufacturer_id = " . $data_model['manufacturer_id']  ." , status = 0,price = '" . (float)$dataImport['productPrice'] . "',image = '" . $imagePath . $dataImport['getImageName'] ."'  where product_id = ". (int)$data_model['product_id'] ."");

             $this->db->query("UPDATE " . DB_PREFIX . "product_description SET meta_title = '" . $dataImport['metaTitle']  . "', name = '" . $this->db->escape($dataImport['productName']) . "',language_id = 1,description = '" . $this->db->escape($dataImport['productDescription']) . "' where product_id = '". (int)$data_model['product_id'] ."'");
       
             $this->db->query("UPDATE " . DB_PREFIX . "product_attribute  SET product_id = '" . (int)$data_model['product_id'] . "', attribute_id = 12 ,language_id = 1,text = 'HARCO' where product_id = '". (int)$data_model['product_id'] ."'");

             $this->db->query("UPDATE " . DB_PREFIX . "product_to_store SET store_id = '0' where product_id = '" . (int)$data_model['product_id'] . "'");

              /*Category Process*/

             $this->updateCategory($dataImport['categoryProducts'],$data_model['product_id']);

              /*Category Process*/
      
         }else{

         //$this->addCategory($mainCategory,$subCategory);

          $this->db->query("INSERT INTO " . DB_PREFIX . "product SET height = '" . $dataImport['height']  . "', width = '" . $dataImport['width']  . "', upc = '" . $dataImport['upc'] ."' , quantity = '" . (int)$key . "', status = 0,model = '". ltrim($dataImport['implodeItemId'][0],'0') ."',price = '" . (float)$dataImport['productPrice'] . "',image = '" . $imagePath . $dataImport['getImageName'] ."'");

            $product_id = $this->db->getLastId();

                $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET meta_title = '" . $dataImport['metaTitle']  . "', product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($dataImport['productName']) . "',language_id = 1,description = '" . $this->db->escape($dataImport['productDescription']) . "'");
           
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute  SET product_id = '" . (int)$product_id . "', attribute_id = 12 ,language_id = 1,text = 'HARCO'");

            /*Category Process*/
            
            $this->insertCategory($dataImport['categoryProducts'],$product_id);

            /*Category Process*/


           $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$data_model['product_id'] . "', store_id = '0");

           /*Manufacturer Process*/

           $this->addManufacturer($dataImport['manufacturerName']);

            /*Manufacturer Process*/           


       }


    

     



   }else{

     

            if($data_model['model'] == $dataImport['implodeItemId'][0]) {
                // $this->addCategory($mainCategory,$subCategory);

                  $this->db->query("UPDATE " . DB_PREFIX . "product SET height = '" . $dataImport['height']  . "', width = '" . $dataImport['width']  . "', upc = '" . $dataImport['upc'] ."' , model = '" . $dataImport['implodeItemId'][0] . "', manufacturer_id = '" . $data_model['manufacturer_id']  ."', status = 1,price = '" . (float)$dataImport['productPrice'] . "' ,image = '" . $imagePath . $dataImport['getImageName'] ."' where product_id = ". (int)$data_model['product_id'] ."");


                    $this->db->query("UPDATE " . DB_PREFIX . "product_description SET meta_title = '" . $dataImport['metaTitle']  . "', product_id = '" . (int)$data_model['product_id'] . "', name = '" . $this->db->escape($dataImport['productName']) . "',language_id = 1,description = '" . $this->db->escape($dataImport['productDescription']) . "' where product_id = '". (int)$data_model['product_id'] ."'");
               
                    $this->db->query("UPDATE " . DB_PREFIX . "product_attribute  SET product_id = '" . (int)$data_model['product_id'] . "', attribute_id = 12 ,language_id = 1,text = 'HARCO' where product_id = '". (int)$data_model['product_id'] ."'");

              /*Category Process*/

              $this->updateCategory($dataImport['categoryProducts'],$data_model['product_id']);

              /*Category Process*/

                   $this->db->query("UPDATE " . DB_PREFIX . "product_to_store SET store_id = '0' where product_id = '" . (int)$data_model['product_id'] . "'");
              }else{


                   $this->db->query("INSERT INTO " . DB_PREFIX . "product SET height = '" . $dataImport['height']  . "', width = '" . $dataImport['width']  . "', upc = '" . $dataImport['upc'] ."' , quantity = '" . (int)$key . "', status = 1,model = '". ltrim($dataImport['implodeItemId'][0],'0') ."',price = '" . (float)$dataImport['productPrice'] . "',image = '" . $imagePath . $dataImport['getImageName'] ."'");

                $product_id = $this->db->getLastId();

                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET meta_title = '" . $dataImport['metaTitle']  . "', product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($dataImport['productName']) . "',language_id = 1,description = '" . $this->db->escape($dataImport['productDescription']) . "'");
               
                 $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute  SET product_id = '" . (int)$product_id . "', attribute_id = 12 ,language_id = 1,text = 'HARCO'");

                 /*Category Process*/

                 $this->insertCategory($dataImport['categoryProducts'],$product_id);

                  /*Category Process*/


                 $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$data_model['product_id'] . "', store_id = '0");


                    
                   /*Manufacturer Process*/  

                    $this->addManufacturer($dataImport['manufacturerName']);

                    /*Manufacturer Process*/

                  }

        
}



 }



  public function insertData($dataImport = array()) {

     $imagePath = 'catalog/demo/';    

     $implode = explode(',', $dataImport['quantitesApi']);
     $key = $implode[0];

        
      if($key < 10)
      {

         /*Manufacturer Process*/ 

         $manufacturer_id =  $this->insertManufacturer($dataImport['manufacturerName']);

          /*Manufacturer Process*/  


            $this->db->query("INSERT INTO " . DB_PREFIX . "product SET height = '" . $dataImport['height']  . "', width = '" . $dataImport['width']  . "', upc = '" . $dataImport['upc'] ."' , quantity = '" . (int)$key . "', status = 0,manufacturer_id = '" . $manufacturer_id ."' ,model = '". ltrim($dataImport['implodeItemId'][0],'0') ."',price = '" . (float)$$dataImport['productPrice'] . "',image = '" . $imagePath . $dataImport['getImageName'] ."',date_added = NOW()");

            $product_id = $this->db->getLastId();

                $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET meta_title = '" . $dataImport['metaTitle']  . "', product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($dataImport['productName']) . "',language_id = 1,description = '" . $this->db->escape($dataImport['productDescription']) . "'");
           
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute  SET product_id = '" . (int)$product_id . "', attribute_id = 12 ,language_id = 1,text = 'HARCO'");

              /*Category Process*/

             $this->insertCategory($dataImport['categoryProducts'],$product_id);

              /*Category Process*/


            $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");

         


      }else
      {

           
        /*Manufacturer Process*/

             $manufacturer_id =  $this->insertManufacturer($dataImport['manufacturerName']);

       /*Manufacturer Process*/     

              $this->db->query("INSERT INTO " . DB_PREFIX . "product SET height = '" . $dataImport['height']  . "', width = '" . $dataImport['width']  . "', upc = '" . $dataImport['upc'] ."' , quantity = '" . (int)$key . "', status = 1,model = '". ltrim($dataImport['implodeItemId'][0],'0') ."', manufacturer_id = '" . $manufacturer_id ."', price = '" . (float)$dataImport['productPrice'] . "',image = '" . $imagePath . $dataImport['getImageName'] ."',date_added = NOW()");

            $product_id = $this->db->getLastId();

                $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET meta_title = '" . $dataImport['metaTitle']  . "', product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($dataImport['productName']) . "',language_id = 1,description = '" . $this->db->escape($dataImport['productDescription']) . "'");
           
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute  SET product_id = '" . (int)$product_id . "', attribute_id = 12 ,language_id = 1,text = 'HARCO'");

             /*Category Process*/

             $this->insertCategory($dataImport['categoryProducts'],$product_id);

             /*Category Process*/

            $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");
        


         }
  
  }

}

?>