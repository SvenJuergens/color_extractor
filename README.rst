
Introduction
============


.. _what-it-does:

What does it do?
----------------

This extension extracts colors from an image.
Based on the https://github.com/thephpleague/color-extractor

.. _screenshots:

Screenshots
-----------

This chapter should help people figure how the extension works. Remove it
if not relevant.

.. figure:: Documentation/Images/sysfile_metadata.png
   :width: 449px
   :alt: View in Backend

   View MetaData of an image Image




Administrator Manual
====================

Target group: **Administrators**

.. _admin-installation:

Installation
------------

Just Install the extension and make an image upload.
The metadata extraction is trigged after upload.

you can use a scheduler task /  "Execute console commands" for existing images.


.. figure:: Documentation/Images/AdministratorManual/ExecuteConsoleCommands.png
   :alt: ExtbaseCommandControllerTask

   View of Scheduler Task in BE (caption of the image)





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
    ... Example 5
    <div class="color-extractor"
     style="padding:10px; min-width: 200px; min-height: 200px;
                background: -webkit-radial-gradient(
                {file.properties.tx_colorextractor_color1} 10%,
                {file.properties.tx_colorextractor_color2} 30%,
                {file.properties.tx_colorextractor_color3} 40%,
                {file.properties.tx_colorextractor_color4} 60%,
                {file.properties.tx_colorextractor_color5} 80%
            )">
    <f:media class="image-embed-item" file="{file}" width="{dimensions.width}" height="{dimensions.height}" alt="{file.alternative}" title="{file.title}" />
  </div>


.. figure:: Documentation/Images/examples.jpg
   :width: 800px
   :alt:  Examples preview

   Examples preview
