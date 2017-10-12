.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _user-manual:

Users Manual
============

Target group: **Editors**

In the frontend you can use the colors, e.g. to create an individual picture frame,
or used the colors as a background color and use a lazyload for the pictures.
or for something complete different

To use the colors in the frontend, do the following

.. code-block:: html

	... Example 1
       <div class="color-extractor"
            style="padding:10px; min-width: 200px; min-height: 200px;
                   background-image: linear-gradient(
                       135deg,
                      {file.properties.tx_colorextractor_color1} 0%, {file.properties.tx_colorextractor_color2} 75%
                   )
   ">
           <f:media class="image-embed-item" file="{file}" width="{dimensions.width}" height="{dimensions.height}" alt="{file.alternative}" title="{file.title}" />
       </div>

   ... Example 2
       <div class="color-extractor"
            style="padding:10px; border: 10px solid {file.properties.tx_colorextractor_color1}">
           <f:media class="image-embed-item" file="{file}" width="{dimensions.width}" height="{dimensions.height}" alt="{file.alternative}" title="{file.title}" />
       </div>

   ... Example 3
       <div class="color-extractor"
            style="padding:10px; border: 10px double {file.properties.tx_colorextractor_color1}">
           <f:media class="image-embed-item" file="{file}" width="{dimensions.width}" height="{dimensions.height}" alt="{file.alternative}" title="{file.title}" />
       </div>

   ... Example 4
       <div class="color-extractor"
            style="padding:1px; border-top: solid 10px {file.properties.tx_colorextractor_color1};
               border-right: solid 10px {file.properties.tx_colorextractor_color2};
               border-bottom: solid 10px {file.properties.tx_colorextractor_color3};
               border-left: solid 10px {file.properties.tx_colorextractor_color4};
           ">
           <f:media class="image-embed-item" file="{file}" width="{dimensions.width}" height="{dimensions.height}" alt="{file.alternative}" title="{file.title}" />
       </div>
   </f:if>

.